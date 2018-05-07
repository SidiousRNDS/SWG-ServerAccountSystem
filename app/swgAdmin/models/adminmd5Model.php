<?php
/*****************************************************************
 * RNDS SWG Server System
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
use \MongoDB\Driver\Query as MongoQuery;

// Use swgAS
use swgAS\config\settings;

class adminmd5Model
{
    private $md5Collection = "tre_md5";

    /**
     * Summary getMD5ById - get the MD5 document by the ID passed
     * @param $args
     * @return mixed
     */
    public function getMD5ById($args)
    {
        try {
            $md5ById = ['_id' => new MongoID($args['id'])];
            $query = new MongoQuery($md5ById);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->md5Collection,$query);
            $md5Data = current($res->toArray());

            return $md5Data;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * Summary getMD5ByName - get the MD5 document by the filename passed
     * @param $args
     * @return mixed
     */
    public function getMD5ByName($args)
    {
        try {
            $md5ByName = ['trefile' => $args['trefile']];
            $query = new MongoQuery($md5ByName);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->md5Collection,$query);
            $md5Data = current($res->toArray());

            return $md5Data;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * Summary updateMD5ById - Update the MD5 Record by the ID padded
     * @param $args
     */
    public function updateMD5ById($args)
    {
        try {
            $updateMD5 = new MongoBulkWrite();
            $updateMD5->update(
                ['_id' => new MongoID($args['md5data']->_id)],
                ['$set' => ['md5' => $args['md5data']->md5]],
                ['multi' => false, 'upsert' => false]
            );
            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->md5Collection, $updateMD5);
        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * Summary createMD5ByName - Create a new MD5 entry based on the name passed
     * @param $args
     * @return MongoID
     */
    public function createMD5ByName($args)
    {
        try {
            $id = new MongoID;
            $createMD5 = new MongoBulkWrite();
            $createMD5->insert(['_id' => $id, 'md5' => $args['md5'], 'trefile' => $args['trefile']]);
            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->md5Collection, $createMD5);

            return $id;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
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