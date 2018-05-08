<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 07 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\models
 * CLASS: adminauthcodeModel
 ******************************************************************/

namespace swgAS\swgAdmin\models;

// Use
use \MongoDB\Driver\Command;
use \MongoDB\Driver\BulkWrite as MongoBulkWrite;
use \MongoDB\BSON\ObjectId as MongoID;
use \MongoDB\Driver\Exception\ConnectionException;
use \MongoDB\Driver\Query as MongoQuery;

// Use swgAS
use swgAS\config\settings;
use swgAS\helpers\messaging\errormsg;
use swgAS\helpers\messaging\statusmsg;
use swgAS\helpers\utilities;
use swgAS\helpers\processauthcodes;
use swgAS\swgAPI\models\accountModel;

class adminauthcodeModel
{

    /**
     * Authcode MongoDB Collection
     * @var string
     */
    private $authcodeCollection = "auth_codes";

    /**
     * @method getAllNotUsedAuthCodes
     * Get a list of all the unused authcodes from the mongodb
     * @param array $args
     * @return string
     */
    public function getAllNotUsedAuthCodes($args)
    {
        try {
            $authCode = ['auth_code_used' => 0];
            $query = new MongoQuery($authCode);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->authcodeCollection,$query);
            $authCodeData = json_encode($res->toArray());

            return $authCodeData;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * @method getAllUsedAuthCodes
     * Get a list of all used authcodes from the mongodb
     * @param array $args
     * @return string
     */
    public function getAllUsedAuthCodes($args)
    {
        try {
            $authCode = ['auth_code_used' => 1];
            $query = new MongoQuery($authCode);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->authcodeCollection,$query);
            $authCodeData = json_encode($res->toArray());

            return $authCodeData;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * @method getAuthCodeById
     * Get a specific authcode entry by its mongoId
     * @param array $args
     * @return mixed
     */
    public function getAuthCodeById($args)
    {
        try {
            $authCode = ['_id' => new MongoID($args['id'])];
            $query = new MongoQuery($authCode);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->authcodeCollection,$query);
            $authCodeData = current($res->toArray());

            return $authCodeData;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * @method getAuthcodeByUsername
     * get any authcodes that have the username set to the user name passed in the args
     * @param array $args
     * @return string
     */
    public function getAuthCodeByUsername($args)
    {
        try {
            $authCode = ['username' =>$args['request']['username']];
            $query = new MongoQuery($authCode);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->authcodeCollection,$query);
            $authCodeData = json_encode($res->toArray());

            return $authCodeData;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * @method createAuthCode
     * Create an authcode and add it to the mongo db collection for the user that is passed in
     * @param array $args
     * @throws \ReflectionException
     */
    public function createAuthCode($args)
    {
        $user = accountModel::checkUsername($args);

        if($user == errormsg::getErrorMsg("noaccount",'accountModel'))
        {
            // Check Auth username to make sure we don't already have a authcode for this user
            $authUserCheck = json_decode($this->getAuthCodeByUsername($args));


            if(empty($authUserCheck))
            {
                $buildAuthCodes = new processauthcodes();
                $authCode = $buildAuthCodes->genereateAuthCode($args['request']);

                if($authCode == "ERROR")
                {
                    return;
                }
                if($authCode == false)
                {
                    $errorMsg = errormsg::getErrorMsg("issuegeneratingauthcode", (new \ReflectionClass(self::class))->getShortName());
                    $args['flash']->addMessageNow("error", $errorMsg);

                    return;
                }

                try {
                    $cDateTime = new \DateTime();

                    $authCodeData = ['_id' => new MongoID,
                        'auth_code' => $authCode,
                        'username' => $args['request']['username'],
                        'email' => $args['request']['email'],
                        'auth_code_used' => 0,
                        'used_date'=>'',
                        'issued_date' => $cDateTime->format('d M Y H:i:s'),
                        'modified_date' => $cDateTime->format('d M Y H:i:s')
                        ];
                    $createAuthCode = new MongoBulkWrite();
                    $createAuthCode->insert($authCodeData);


                    $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->authcodeCollection, $createAuthCode);

                    if ($res->getInsertedCount() == 1) {
                        $statusMsg = statusmsg::getStatusMsg("authcreated", (new \ReflectionClass(self::class))->getShortName());
                        $statusMsg = utilities::replaceStatusMsg($statusMsg, "::USERNAME::", $args['request']['username']);
                        $statusMsg = utilities::replaceStatusMsg($statusMsg, "::AUTHCODE::", $authCode);

                        $args['flash']->addMessageNow("success", $statusMsg);
                    } else {
                        $args['flash']->addMessageNow("error", errormsg::getErrorMsg("authnotgenerated", (new \ReflectionClass(self::class))->getShortName()));
                    }

                    return;
                } catch(ConnectionException $ex) {
                    $args['flash']->addMessageNow("error", $ex->getMessage());
                }
            }
        }
    }

    /**
     * @method updateAuthCode
     * Update authcode
     * @param array $args
     * @throws \ReflectionException
     */
    public function updateAuthCode($args)
    {
        try {
            $cDateTime = new \DateTime();
            $updateAuthCode = new MongoBulkWrite();
            if($args['request']['active'] == 1)
            {

                $updateAuthCode->update(
                    ['_id' => new MongoID($args['request']['id'])],
                    ['$set' => [
                        'useranme' => $args['request']['username'],
                        'email'=>$args['request']['email'],
                        'auth_code_used' => 1,
                        'used_date' => $cDateTime->format('d M Y H:i:s'),
                        'modified_date' => $cDateTime->format('d M Y H:i:s')
                    ]],
                    ['multi' => false, 'upsert' => false]
                );
            }
            else {
                $updateAuthCode->update(
                    ['_id' => new MongoID($args['request']['id'])],
                    ['$set' => ['useranme' => $args['request']['username'],'email'=>$args['request']['email'],'modified_date' => $cDateTime->format('d M Y H:i:s')]],
                    ['multi' => false, 'upsert' => false]
                );
            }

            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->authcodeCollection, $updateAuthCode);

            $statusMsg = statusmsg::getStatusMsg("authcodeupdated", (new \ReflectionClass(self::class))->getShortName());
            $statusMsg = utilities::replaceStatusMsg($statusMsg, "::USERNAME::", $args['request']['username']);

            $args['flash']->addMessage("success", $statusMsg);

        } catch(ConnectionException $ex) {
            $args['flash']->addMessage("error", $ex->getMessage());
        }
    }

    /**
     * @method deleteAuthCode
     * Delete a specific authcode by its Id
     * @param array $args
     * @throws \ReflectionException
     */
    public function deleteAuthCode($args)
    {
        try {
            $authCode = new adminauthcodeModel();
            $authCodeData = $authCode->getAuthCodeById($args);

            $authCodeUsername = $authCodeData->username;

            $deleteAuthCode = new MongoBulkWrite();
            $deleteAuthCode->delete(['_id' => new MongoID($args['id'])], ['limit' => 1]);

            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->authcodeCollection, $deleteAuthCode);

            $statusMsg = statusmsg::getStatusMsg("authcodedeleted", (new \ReflectionClass(self::class))->getShortName());
            $statusMsg = utilities::replaceStatusMsg($statusMsg, "::USERNAME::",$authCodeUsername);

            $args['flash']->addMessage("success", $statusMsg);

        } catch(ConnectionException $ex) {
            $args['flash']->addMessage("error", $ex->getMessage());
        }
    }
}