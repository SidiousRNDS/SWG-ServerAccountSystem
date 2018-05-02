<?php
    /*****************************************************************
     * RNDS SWG Account System
     * @author:  Sidious <sidious@rnds.io>
     * @since:   30 April 2018
     * @link:    https://github.com/SidiousRNDS/SWGRO-AccountSystem
     * @version: 1.0.0
     *****************************************************************
     * NAMESPACE: swgAS\swgAdmin\models
     * CLASS: admingameupdatesModel
     *****************************************************************/
    
    namespace swgAS\swgAdmin\models;

    // Use
    use \MongoDB\Driver\Command;
    use \MongoDB\Driver\BulkWrite as MongoBulkWrite;
    use \MongoDB\BSON\ObjectId as MongoID;
    use \MongoDB\Driver\Exception\ConnectionException;
    use \MongoDB\Driver\Query as MongoQuery;

    // Use swgAS
    use swgAS\config\settings;
    use swgAS\helpers\utilities;
    use swgAS\helpers\messaging\errormsg;
    use swgAS\helpers\messaging\statusmsg;
    use swgAS\helpers\patch;
    use swgAS\helpers\processgameconfigs;

    class admingameupdatesModel
    {
        // TODO gameupdates will be stored in the mongoDB one collection will be for server gameupdates and one will be for launcher gameupdates
        // TODO we will need to make an API to access these entries as well for use in the launcher and the forums - (forums may be a direct input but we will
        // TODO also need to setup an API call so that other forums other then myBBS can use them as well so the users only have to make a single entry in this system
        // TODO to push out update information to their system. After launch we may look at doing intrgrations with other bbs system but at this time we will just create
        // TODO the API)
    
        private $serverUpdateCollection = "server_updates";
        private $launcherUpdateCollection = "launcher_updates";
        private $clientUpdateCollection = "client_updates";


        /**
         * Summary getPatchByName - Get a patch by its name and return the results
         * @param $args
         * @return mixed
         */
        public function getPatchByName($args)
        {
            try {
                $patch = ['patch_title' =>$args['patch_title']];
                $query = new MongoQuery($patch);
                $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$args['collection'],$query);
                $patchData = current($res->toArray());

                return $patchData;

            } catch (ConnectionException $ex) {
                $args['flash']->addMessageNow("error", $ex->getMessage());
            }
        }

        /**
         * Summary addServerPatch - Add the record to the mongoDB and setup the TRE file to the correct location on the server
         * @param $args
         * @throws \ReflectionException
         */
        public function addServerPatch($args)
        {
            if (count($args['request']) == 0) {
                    $errorMsg = errormsg::getErrorMsg("uploadfailed", (new \ReflectionClass(self::class))->getShortName());
                    $args['flash']->addMessageNow("error", $errorMsg);

                    return;
            }

            if ($args['request']['updateTitle'] == "") {
                $errorMsg = errormsg::getErrorMsg("patchmissingtitle", (new \ReflectionClass(self::class))->getShortName());
                $args['flash']->addMessageNow("error", $errorMsg);

                return;
            }

            if ($args['request']['updateNotes'] == "") {
                $errorMsg = errormsg::getErrorMsg("patchmissingnotes", (new \ReflectionClass(self::class))->getShortName());
                $args['flash']->addMessageNow("error", $errorMsg);

                return;
            }

            $args['collection'] = $this->serverUpdateCollection;
            $args['patch_title'] = $args['request']['updateTitle'];

            if ($this->getPatchByName($args))
            {
                $errorMsg = errormsg::getErrorMsg("patchalreadyexists", (new \ReflectionClass(self::class))->getShortName());
                $errorMsg = utilities::replaceStatusMsg($errorMsg, "::PATCHNAME::", $args['request']['updateTitle']);
                $args['flash']->addMessageNow("error", $errorMsg);

                return;
            }

            try {

                $cDateTime = new \DateTime();

                $serverPatch = ['_id' => new MongoID, 'patch_title' => $args['request']['updateTitle'], 'patch_notes' => $args['request']['updateNotes'], 'patch_date' => $cDateTime->format('d M Y H:i:s')];
                $createServerPatch = new MongoBulkWrite();
                $createServerPatch->insert($serverPatch);
                $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->serverUpdateCollection, $createServerPatch);


                if ($res->getInsertedCount() == 1) {

                    $statusMsg = statusmsg::getStatusMsg("serverpatchcreated", (new \ReflectionClass(self::class))->getShortName());
                    $statusMsg = utilities::replaceStatusMsg($statusMsg, "::PATCHNAME::", $args['request']['updateTitle']);


                    if ($args['file']['updateTreFile']->getClientFilename() != "") {

                        $patchUtils = new patch();
                        $isUploaded = $patchUtils->moveTreFile($args['file']);

                        if ($isUploaded == true) {

                            $processGameConfig = new processgameconfigs();
                            $fileUpdated = $processGameConfig->liveConfig($args['file']);

                            if ($fileUpdated) {
                                $args['flash']->addMessageNow("success", $statusMsg);
                            } else {
                                $errorMsg = errormsg::getErrorMsg("liveconfigfailed", (new \ReflectionClass(self::class))->getShortName());
                                $errorMsg = utilities::replaceStatusMsg($errorMsg, "::PATCHNAME::", $args['request']['updateTitle']);
                                $args['flash']->addMessageNow("error", $errorMsg);
                            }
                        }
                        else {
                            $errorMsg = errormsg::getErrorMsg("uploadfailed", (new \ReflectionClass(self::class))->getShortName());
                            $errorMsg = utilities::replaceStatusMsg($errorMsg, "::PATCHNAME::", $args['request']['updateTitle']);
                            $args['flash']->addMessageNow("error", $errorMsg);
                        }
                    }
                    else {
                        $args['flash']->addMessageNow("success", $statusMsg);
                    }

                }
                else {
                    $errorMsg = errormsg::getErrorMsg("serverpatchnotcreated", (new \ReflectionClass(self::class))->getShortName());
                    $errorMsg = utilities::replaceStatusMsg($errorMsg, "::PATCHNAME::", $args['request']['updateTitle']);
                    $args['flash']->addMessageNow("error", $errorMsg);
                }
            } catch(ConnectionException $ex) {
                $args['flash']->addMessageNow("error", $ex->getMessage());

            }
            return;
        }

        /**
         * Summary getServerPatches - List all the server patches that have been put in the system
         * @param $args
         * @return string
         */
        public function getServerPatches($args)
        {
            try {
                $mongoCommand = new Command(array('find'=>$this->serverUpdateCollection));
                $mongoCursor = $args['mongodb']->executeCommand(settings::MONGO_ADMIN,$mongoCommand);

                $patches = $mongoCursor->toArray();

                return json_encode($patches);

            } catch (ConnectionException $ex) {
                $args['flash']->addMessageNow("error", $ex->getMessage());
            }
        }
    }