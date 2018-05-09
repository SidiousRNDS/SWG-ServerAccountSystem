<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 04 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\helpers
 * CLASS: loadstarterdataModel
 ******************************************************************/

namespace swgAS\swgLoad;

use swgAS\swgAdmin\models\adminmd5Model;
use swgAS\swgAdmin\models\adminroleModel;
use swgAS\swgAdmin\models\adminusersModel;
use swgAS\swgAPI\models\accountModel;

/**
 * Class loadstarterdataModel
 * @package swgAS\swgLoad
 */
class loadstarterdataModel
{
    
    /**
     * @method initLoad
     * Load inital data for MongoDB and alter Mysql Accounts Table
     * @param array $args
     */
    public static function initload($args)
    {
        // Load MD5 into mongo
        $md5Model = new adminmd5Model();

        echo"Loading MD5 Entries\n";
        echo"**********************************************************\n";
        $md5loaded = $md5Model->loadMD5Data($args);
        $loadedMD5Records = $md5loaded-1;
        $totalMd5Records = count(json_decode($args['md5Data']));
        if( $totalMd5Records == $loadedMD5Records)
        {
            echo $loadedMD5Records ." out of ". $totalMd5Records ." MD5 Entries were loaded.\n\n";
        }

        // Load Roles - Owner
        $rolesModel = new adminroleModel();
        echo"Loading Roles Entries\n";
        echo"**********************************************************\n";
        $rolesModel->loadRoleData($args);
        echo"Owner Roles has been created.\n\n";

        // Load base Users - Admin / Owner
        $usersModel = new adminusersModel();
        echo"Loading Users Entries\n";
        echo"**********************************************************\n";
        $usersModel->loadUserData($args);
        echo"Admin User has been created.\n\n";
        
        // Alter Accounts Table
        echo"Altering Accounts Table\n";
        echo"**********************************************************\n";
        $alterAccountTbl = accountModel::alterAccountsTable($args);
        if($alterAccountTbl)
        {
            echo"Accounts Table has been altered with the required columns added.\n\n";
        }
        else {
            echo"Accounts Table has not been altered with the required columns.\n\n";
        }
        
    }
}