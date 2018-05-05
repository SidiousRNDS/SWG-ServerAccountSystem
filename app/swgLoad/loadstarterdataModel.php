<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 04 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\helpers
 * CLASS: loadstarterdataModel
 ******************************************************************/

namespace swgAS\swgLoad;

// Use
use \MongoDB\Driver\BulkWrite as MongoBulkWrite;
use \MongoDB\BSON\ObjectId as MongoID;

// Use swgAS
use swgAS\config\settings;

class loadstarterdataModel
{
    private static $md5Collection = "tre_md5";

    public static function initloadTreMD5($args)
    {
        $md5File = json_decode($args['md5Data'], true);
        $mongo = $args['mongodb'];

        $createMD5TreBulk = new MongoBulkWrite(['ordered' => true]);
        // Remove Existing Entries
        $createMD5TreBulk->delete([]);

        $x=1;
        foreach($md5File as $index => $value)
        {
            if(is_array($value) && $value['md5'] != "") {
                $createMD5TreBulk->insert(['_id' => new MongoID, 'md5' => $value['md5'], 'trefile' => $value['trefile']]);
                print $x. ". Adding entry for " . $value['trefile'] . "\n";
            }
            $x++;
        }

        $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . static::$md5Collection, $createMD5TreBulk);

        return [count($createMD5TreBulk)-1, count($md5File)];
    }
}