<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 01 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\helpers
 * CLASS: processgameconfigs
 ******************************************************************/

namespace swgAS\helpers;

// Use
use WriteiniFile\WriteiniFile;

// Use swgAS
use swgAS\config\settings;

class processgameconfigs
{
    /**
     * @param $args
     * @return bool
     * @throws \Exception
     */
    public function liveConfig($args)
    {
        return $this->processLiveConfig($args);
    }
    
    /**
     * @param $args
     * @return bool
     * @throws \Exception
     */
    private function processLiveConfig($args)
    {
        $treFile = $args['updateTreFile'];
        $treFileName = $treFile->getClientFilename();

        $args['configFile'] = settings::LIVE_CONFIG_PATH;
        $readConfigData = $this->readIniConfig($args);

        $newSharedFile = [];
        foreach($readConfigData['SharedFile'] as $key => $value)
        {
            if ($key == 'maxSearchPriority')
            {
                $newSharedFile['maxSearchPriority'] = (int) $value + 1;
                $newSharedFile['searchTree_00_'.$value] = $treFileName;
            }
            else {
                $newSharedFile[$key] = $value;
            }
        }

        // Rebuild the Config file with the updated data
        $updateConfigData = $readConfigData;
        $updateConfigData['SharedFile'] = $newSharedFile;

        $args['newConfigData'] = $updateConfigData;

        $writeConfig = $this->writeIniConfig($args);

        return $writeConfig;
    }

    /**
     * Summary readIniConfg
     * @param $args
     * @return array|bool
     */
    private function readIniConfig($args)
    {
        return parse_ini_file(settings::UPDATE_PATH."/".$args['configFile'], true);
    }

    /**
     * Summary writeIniConfig
     * @param $args
     * @return bool
     * @throws \Exception
     */
    private function writeIniConfig($args)
    {
        try {
            unlink(settings::UPDATE_PATH . "/" . $args['configFile']);
            
            $createNewConfig = new WriteiniFile(settings::UPDATE_PATH . "/" . $args['configFile']);
            $createNewConfig->add($args['newConfigData']);
            $createNewConfig->write();
            
            return true;
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
    
    private function readXMLConfig($args)
    {
    
    }
    
    private function writeXMLConfig($args)
    {
    
    }
}