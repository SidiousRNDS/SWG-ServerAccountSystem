<?php
    /*****************************************************************
     * RNDS
     * @author : Sidious <sidious@rnds.io>
     * @since  : 03 May 2018
     * @link   : https://github.com/SidiousRNDS/
     * @version: 1.0.0
     *****************************************************************
     * NAMESPACE: swgAS\swgAdmin\models
     * CLASS: adminserverupdatesModel
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
    use swgAS\helpers\movefiles;
    use swgAS\helpers\processgameconfigs;
    use swgAS\swgAdmin\models\admingameupdatesutilsModel;
    
    class adminserverupdatesModel
    {
        private $serverUpdateCollection = "server_updates";
        
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
                    
                        $patchUtils = new movefiles();
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
    
        /**
         * Summary getServerPatchById
         * @param $args
         * @return mixed
         */
        public function getServerPatchById($args)
        {
            $args['collection'] = $this->serverUpdateCollection;
            $adminGameUpdateUtils = new admingameupdatesutilsModel();
            
            return json_encode($adminGameUpdateUtils->getPatchById($args));
        }
    }