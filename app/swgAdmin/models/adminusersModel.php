<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 12 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\models
 * CLASS: adminusersModel
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
use swgAS\helpers\utilities;
use swgAS\helpers\messaging\errormsg;
use swgAS\helpers\messaging\statusmsg;
use swgAS\helpers\password;

/**
 * Class adminusersModel
 * @package swgAS\swgAdmin\models
 */
class adminusersModel
{
    /**
     * @var string
     */
    private $usersCollection = "users";

    /**
     * @method  addUser
     * Add a new helper to the DB
     * @param array $args
     * @throws \ReflectionException
     */
    public function addUser($args)
    {
        $this->userFormCheck($args);

        try {

            $pass = new password();
            $args['salt'] = settings::ADMIN_PASSWORD_SALT;

            $encryptedPassword = $pass->generateEncryptedPassword($args);

            $user = ['_id' => new MongoID, 'username' => $args['username'], 'password' => $encryptedPassword['passwordHash'], 'email' => $args['email'], 'avatar' => '', 'role'=>$args['role']];
            $createUser = new MongoBulkWrite();
            $createUser->insert($user);


            $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->usersCollection, $createUser);

            if ($res->getInsertedCount() == 1) {
                $statusMsg = statusmsg::getStatusMsg("usercreated", (new \ReflectionClass(self::class))->getShortName());
                $statusMsg = utilities::replaceStatusMsg($statusMsg, "::USERNAME::", $args['username']);

                $args['flash']->addMessageNow("success", $statusMsg);
            } else {
                $errorMsg = errormsg::getErrorMsg("usernotcreated", (new \ReflectionClass(self::class))->getShortName());
                $errorMsg = utilities::replaceStatusMsg($errorMsg, "::USERNAME::", $args['username']);
                $args['flash']->addMessageNow("error", $errorMsg);
            }

            return;
        } catch(ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    // TODO finish this method so we can check to make sure we don't duplicate the user
    public function checkUser($args)
    {
        //$checkUser = ['usernme' => $args['username']];

    }

    /**
     * @method  getUsers
     * @param array $args
     * @return string
     */
    public function getUsers($args)
    {
        try {
            $mongoCommand = new Command(array('find'=>$this->usersCollection));
            $mongoCursor = $args['mongodb']->executeCommand(settings::MONGO_ADMIN,$mongoCommand);

            $users = $mongoCursor->toArray();

            return json_encode($users);

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * @method  getUser
     * @param array $args
     * @return mixed
     */
    public function getUser($args)
    {
        try {
            $user = ['_id' => new MongoID($args['id'])];
            $query = new MongoQuery($user);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->usersCollection,$query);
            $userData = current($res->toArray());

            return $userData;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * @method  getUserByName
     * @param array $args
     * @return mixed
     */
    public function getUserByName($args)
    {
        try {
            $user = ['username' => $args['username']];
            $query = new MongoQuery($user);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->usersCollection,$query);
            $userData = current($res->toArray());

            return $userData;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * @method  updateUser
     * @param array $args
     * @throws \ReflectionException
     */
    public function updateUser($args)
    {
        $this->userFormCheck($args);

        try {

            $updateUser = new MongoBulkWrite();

            if($args['changepassword'] == "on") {
                $pass = new password();
                $args['salt'] = settings::ADMIN_PASSWORD_SALT;

                $encryptedPassword = $pass->generateEncryptedPassword($args);

                $updateUser->update(
                    ['_id' => new MongoID($args['id'])],
                    ['$set' => ['email' => $args['email'], 'password' => $encryptedPassword['passwordHash'], 'role' => $args['role']]],
                    ['multi' => false, 'upsert' => false]
                );
            }
            else {
                $updateUser->update(
                    ['_id' => new MongoID($args['id'])],
                    ['$set' => ['email' => $args['email'], 'role' => $args['role']]],
                    ['multi' => false, 'upsert' => false]
                );
            }

            $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->usersCollection, $updateUser);

            $statusMsg = statusmsg::getStatusMsg("userupdated", (new \ReflectionClass(self::class))->getShortName());
            $statusMsg = utilities::replaceStatusMsg($statusMsg, "::USERNAME::", $args['username']);

            $args['flash']->addMessage("success", $statusMsg);
        } catch(ConnectionException $ex) {
            $args['flash']->addMessage("error", $ex->getMessage());
        }
    }

    /**
     * @method deleteUser
     * @param array $args
     * @throws \ReflectionException
     */
    public function deleteUser($args)
    {
        $userData = $this->getUser($args);
        $username = $userData->username;

        try {
            $deleteUser = new MongoBulkWrite();
            $deleteUser->delete(['_id' => new MongoID($args['id'])], ['limit' => 1]);

            $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->usersCollection, $deleteUser);

            $statusMsg = statusmsg::getStatusMsg("userdeleted", (new \ReflectionClass(self::class))->getShortName());
            $statusMsg = utilities::replaceStatusMsg($statusMsg, "::USERNAME::", $username);

            $args['flash']->addMessage("success", $statusMsg);

        } catch(ConnectionException $ex) {
            $args['flash']->addMessage("error", $ex->getMessage());
        }
    }

    /**
     * @method  userFormCheck
     * Check the data that was passed by the form
     * @param array $args
     * @throws \ReflectionException
     */
    private function userFormCheck($args)
    {
        if (isset($args['username'])) {
            if($args['username'] == "") {
                $errorMsg = errormsg::getErrorMsg("usernotcreatedmissing", (new \ReflectionClass(self::class))->getShortName());
                $errorMsg = utilities::replaceStatusMsg($errorMsg, "::MISSING::", 'Username');
                $args['flash']->addMessageNow("error", $errorMsg);

                return;
            }
        }

        if ($args['password'] != $args['repassword']) {
            $errorMsg = errormsg::getErrorMsg("usernotcreatednomatch", (new \ReflectionClass(self::class))->getShortName());
            $args['flash']->addMessageNow("error", $errorMsg);

            return;
        }

        if($args['role'] == "Select a Role" || $args['role'] == "") {
            $errorMsg = errormsg::getErrorMsg("usernotcreatednorole", (new \ReflectionClass(self::class))->getShortName());
            $args['flash']->addMessageNow("error", $errorMsg);

            return;
        }

        if($args['email'] == "") {
            $errorMsg = errormsg::getErrorMsg("usernotcreatednoemail", (new \ReflectionClass(self::class))->getShortName());
            $args['flash']->addMessageNow("error", $errorMsg);

            return;
        }
    }

    /**
     * @method loadUserData
     * Load inital User Data
     * @param array $args
     */
    public function loadUserData($args)
    {
        $args['username'] = "admin";
        $args['email'] = "admin@yourdomain.com";
        $args['password'] = "123456";
        $args['role'] = "Owner";

        $pass = new password();
        $args['salt'] = settings::ADMIN_PASSWORD_SALT;

        $encryptedPassword = $pass->generateEncryptedPassword($args);

        //print_r($args);
        $user = ['_id' => new MongoID, 'username' => $args['username'], 'password' => $encryptedPassword['passwordHash'], 'email' => $args['email'], 'avatar' => '', 'role'=>$args['role']];
        $createUser = new MongoBulkWrite();
        $createUser->insert($user);


        $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->usersCollection, $createUser);
    }
}