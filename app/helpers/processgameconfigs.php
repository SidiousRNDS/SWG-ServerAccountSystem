<?php
/*****************************************************************
 * RNDS SWG Server System
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
use \WriteiniFile\WriteiniFile;

// Use swgAS
use swgAS\config\settings;

/**
 * Class processgameconfigs
 * @package swgAS\helpers
 */
class processgameconfigs
{
    /**
     * @method gameLiveConfig
     * @param array $args
     * @return bool
     * @throws \Exception
     */
    public function gameLiveConfig($args)
    {
        return $this->processLiveConfig($args);
    }

    /**
     * @method processLiveConfig
     * @param array $args
     * @return bool
     * @throws \Exception
     */
    private function processLiveConfig($args)
    {
        $treFile = $args['file']['updateTreFile'];
        $treFileName = $treFile->getClientFilename();
        $configFile = settings::LIVE_CONFIG_FILE;
        $configPath = settings::UPDATE_LIVE_PATH;

        if($args['request']['updateforserver'] == settings::TEST_GAME_SERVER) {
            $configFile = settings::TEST_CONFIG_FILE;
            $configPath = settings::UPDATE_TEST_PATH;
        }
        
        $args['configFile'] = $configFile;
        $args['configPath'] = $configPath;
        
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
     * @method readIniConfig
     * @param array $args
     * @return array|bool
     */
    private function readIniConfig($args)
    {
        return parse_ini_file( $args['configPath']."/".$args['configFile'], true);
    }

    /**
     * @method writeIniConfig
     * @param array $args
     * @return bool
     * @throws \Exception
     */
    private function writeIniConfig($args)
    {
        try {
            unlink( $args['configPath'] . "/" . $args['configFile']);
            
            $createNewConfig = new WriteiniFile( $args['configPath'] . "/" . $args['configFile']);
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