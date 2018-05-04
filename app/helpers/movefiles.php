<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 01 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\helpers
 * CLASS: movefiles
 ******************************************************************/

namespace swgAS\helpers;

// Use

// Use swgAS
use swgAS\config\settings;

class movefiles
{
    /**
     * @param $args
     * @return bool
     * @throws \Exception
     */
    public function moveTreFile($args)
    {
        $treFile = $args['file']['updateTreFile'];
        $treFileName = $treFile->getClientFilename();
        $server = $args['request']['updateforserver'];
        $trePath = settings::UPDATE_LIVE_PATH;

        try {
            if($server == settings::TEST_GAME_SERVER) {
                $trePath = settings::UPDATE_TEST_PATH;
            }
            
            $treFile->moveTo($trePath . "/" . $treFileName);
    
            // Update MD5 Checksum
            return utilities::md5CheckSum($trePath . "/" . $args['file']['updateTreFile']->getClientFilename());
            
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return false;
    }
}