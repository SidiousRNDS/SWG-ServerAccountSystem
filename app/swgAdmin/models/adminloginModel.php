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
use \MongoDB\Driver\Query as Query;

// Use swgAS
use swgAS\config\settings;
use swgAS\utils\errormsg;
use swgAS\utils\password;
use swgAS\utils\usersessions;

class adminloginModel extends Model
{

    /**
     * Summary authLogin - Admin login check
     * @param $args
     * @return bool|string
     * @throws \ReflectionException
     */
    public function authLogin($args)
    {
        $pass = new password();
        $args['salt'] = settings::ADMIN_PASSWORD_SALT;

        $encryptedPassword = $pass->generateEncryptedPassword($args);

        $loginFilter = ['username' => $args['username'], 'password' => $encryptedPassword['passwordHash']];

        $query = new Query($loginFilter);

        $res = $args['mongodb']->executeQuery("swgASAdmin.users",$query);
        $user = current($res->toArray());

        if(empty($user))
        {
            return errormsg::getErrorMsg("notauthorized", (new \ReflectionClass(self::class))->getShortName());
        }

        // Add User to the user_session collection
        $userSession = new usersessions();
        $userSession->setUserSession($args);

        return true;
    }
}