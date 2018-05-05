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
    
    namespace swgAS\swgAdmin\models\gameupdates;

    // Use
    use \MongoDB\Driver\Command;
    use \MongoDB\Driver\BulkWrite as MongoBulkWrite;
    use \MongoDB\BSON\ObjectId as MongoID;
    use \MongoDB\Driver\Exception\ConnectionException;

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
            $patchUtils = new admingameupdatesutilsModel();
            
            if (count($args['request']) == 0) {
                $errorMsg = errormsg::getErrorMsg("uploadfailed", (new \ReflectionClass(self::class))->getShortName());
                $args['flash']->addMessageNow("error", $errorMsg);
            
                return;
            }
        
            if ($args['request']['updateforserver'] == "Select the server this patch is for" || $args['request']['updateforserver'] == "") {
                $errorMsg = errormsg::getErrorMsg("patchmissingserver", (new \ReflectionClass(self::class))->getShortName());
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
        
            if ($patchUtils->getPatchByName($args))
            {
                $errorMsg = errormsg::getErrorMsg("patchalreadyexists", (new \ReflectionClass(self::class))->getShortName());
                $errorMsg = utilities::replaceStatusMsg($errorMsg, "::PATCHNAME::", $args['request']['updateTitle']);
                $args['flash']->addMessageNow("error", $errorMsg);
            
                return;
            }
        
            try {
            
                $cDateTime = new \DateTime();
                $treUpdate = "";
                
                if ($args['file']['updateTreFile']->getClientFilename() != "") {
                    $treUpdate = $args['file']['updateTreFile']->getClientFilename();
                }
                
                $id = new MongoID;
                
                $serverPatch = ['_id' => $id, 'patch_title' => $args['request']['updateTitle'],
                                'patch_notes' => $args['request']['updateNotes'], 'patch_date' => $cDateTime->format('d M Y H:i:s'),
                                'patch_tre_update' => $treUpdate, 'patch_tre_md5' => '', 'patch_server' => $args['request']['updateforserver']];
                
                $createServerPatch = new MongoBulkWrite();
                $createServerPatch->insert($serverPatch);
                $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->serverUpdateCollection, $createServerPatch);
            
            
                if ($res->getInsertedCount() == 1) {
                
                    $statusMsg = statusmsg::getStatusMsg("serverpatchcreated", (new \ReflectionClass(self::class))->getShortName());
                    $statusMsg = utilities::replaceStatusMsg($statusMsg, "::PATCHNAME::", $args['request']['updateTitle']);
                
                
                    if ($args['file']['updateTreFile']->getClientFilename() != "") {
                    
                        $patchUtils = new movefiles();
                        $md5CheckValue = $patchUtils->moveTreFile($args);
                    
                        if ($md5CheckValue != false) {
    
                            $serverUpdateMD5 = new MongoBulkWrite();
                            $serverUpdateMD5->update(
                                ['_id' => new MongoID($id)],
                                ['$set' => ['patch_tre_md5' => $md5CheckValue]],
                                ['multi' => false, 'upsert' => false]
                            );
                            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->serverUpdateCollection, $serverUpdateMD5);
                            
                            $processGameConfig = new processgameconfigs();
                            
                            $fileUpdated = $processGameConfig->gameLiveConfig($args);
                        
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