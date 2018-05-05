<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 04 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\models
 * CLASS: adminmd5Model
 ******************************************************************/

namespace swgAS\swgAdmin\models;

// Use
use \MongoDB\Driver\BulkWrite as MongoBulkWrite;
use \MongoDB\BSON\ObjectId as MongoID;

// Use swgAS
use swgAS\config\settings;

class adminmd5Model
{
    private $md5Collection = "tre_md5";

    public function getMD5ById($args)
    {

    }

    public function getMD5ByName($argS)
    {

    }

    public function setMD5($args)
    {

    }

    public function getAllTreMD5($args)
    {

    }

    /**
     * Summary loadMD5Data - This is only used to load in base data for the TRE files
     * @param $args
     * @return mixed
     */
    public function loadMD5Data($args)
    {
        $md5File = json_decode($args['md5Data'], true);
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

        $loaded = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->md5Collection, $createMD5TreBulk);

        return count($createMD5TreBulk);
    }
}