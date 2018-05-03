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
        $treFile = $args['updateTreFile'];
        $treFileName = $treFile->getClientFilename();

        try {
            $treFile->moveTo(settings::UPDATE_PATH . "/" . $treFileName);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return true;
    }
}