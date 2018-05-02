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

// Use swgAS
use swgAS\config\settings;

class processgameconfigs
{
    /**
     * @param $args
     * @return bool
     */
    public function liveConfig($args)
    {
        return $this->processLiveConfig($args);
    }

    private function processLiveConfig($args)
    {
        $treFile = $args['updateTreFile'];
        $treFileName = $treFile->getClientFilename();

        $args['configFile'] = settings::LIVE_CONFIG_PATH;
        $readConfigData = $this->readConfig($args);

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

        $writeConfig = $this->writeConfig($args);

        return $writeConfig;
    }

    /**
     * @param $args
     * @return array|bool
     */
    private function readConfig($args)
    {
        return parse_ini_file(settings::UPDATE_PATH."/".$args['configFile'], true);
    }

    /**
     * @param $args
     * @return bool
     * @throws \Exception
     */
    private function writeConfig($args)
    {
        try {
            unlink(settings::UPDATE_PATH . "/" . $args['configFile']);
            $configWrite = fopen(settings::UPDATE_PATH . "/" . $args['configFile'], 'w');

            foreach ($args['newConfigData'] as $key => $value) {
                $iniHeader = "[" . $key . "]\n\n";
                fwrite($configWrite, $iniHeader);

                if (is_array($value)) {
                    foreach ($value as $itemKey => $itemValue) {
                        $itemHeader = "[" . $itemKey . "]";
                        $itemData = "\t" . $itemHeader . "=" . $itemValue . "\n";
                        fwrite($configWrite, $itemData);
                    }
                    fwrite($configWrite, "\n\n");
                }
            }
            fclose($configWrite);

            return true;
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}