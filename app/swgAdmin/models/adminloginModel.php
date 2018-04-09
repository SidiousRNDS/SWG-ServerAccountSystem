<?php

namespace swgAS\swgAdmin\models;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 05 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\models
 * CLASS: adminloginModel
 ******************************************************************/

// Use
use \Illuminate\Database\Eloquent\Model as Model;
use \MongoDB\Driver\Query as MongoQuery;

// Use swgAS
use swgAS\config\settings;
use swgAS\utils\messaging\errormsg;
use swgAS\utils\password;
use swgAS\utils\security;
use swgAS\utils\sessions;

class adminloginModel extends Model
{
    private $usersCollection = "users";

    /**
     * Summary authLogin - Admin login check
     * @param $args
     * @return bool|string
     * @throws \ReflectionException
     */
    public function authLogin($args)
    {
        // Check if there is a lock on the account
        $security = new security();
        $lock = $security->checkLocks($args);

        if(empty($lock)) {
            // Check User information
            $pass = new password();
            $args['salt'] = settings::ADMIN_PASSWORD_SALT;

            $encryptedPassword = $pass->generateEncryptedPassword($args);

            $loginFilter = ['username' => $args['username'], 'password' => $encryptedPassword['passwordHash'], 'ip' => $args['userIP']];

            $query = new MongoQuery($loginFilter);

            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN . "." . $this->usersCollection, $query);
            $user = current($res->toArray());

            if (empty($user)) {
                // Add Login Attempts
                $security->loginAttempts($args);
                return errormsg::getErrorMsg("notauthorized", (new \ReflectionClass(self::class))->getShortName());
            }

            // Add User to the user_session collection
            $userSession = new sessions();
            $userSession->setUserSession($args);

            return true;
        }

        return $lock;
    }
}