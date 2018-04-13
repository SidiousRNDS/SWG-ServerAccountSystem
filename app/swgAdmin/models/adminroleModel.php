<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 12 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\models
 * CLASS: adminroleModel
 ******************************************************************/

namespace swgAS\swgAdmin\models;

// Use
use \MongoDB\Driver\Command;
use \MongoDB\Driver\BulkWrite as MongoBulkWrite;
use \MongoDB\BSON\ObjectId as MongoID;
use \MongoDB\Driver\Exception\ConnectionException;

// Use swgAS
use swgAS\config\settings;
use swgAS\utils\utilities;
use swgAS\utils\messaging\errormsg;
use swgAS\utils\messaging\statusmsg;

class adminroleModel
{
    private $roleCollection = "user_roles";


    /**
     * Summary getRoles - Get all the roles in the user_roles collection
     * @param $args
     * @return \MongoCursor
     * @throws \Exception
     */
    public function getRoles($args)
    {
        try {
            $mongoCommand = new Command(array('find'=>$this->roleCollection));
            $mongoCursor = $args['mongodb']->executeCommand(settings::MONGO_ADMIN,$mongoCommand);

            return json_encode($mongoCursor->toArray());

        } catch (ConnectionException $ex) {
            $args['flash']->addMessage("error", $ex->getMessage());
        }

    }

    /**
     * Summary addRole - Add new role to the user_roles collection
     * @param $args
     * @throws \ReflectionException
     */
    public function addRole($args)
    {
        if ($args['request']['rolename'] == "") {
            $errorMsg = errormsg::getErrorMsg("rolenotecreated", (new \ReflectionClass(self::class))->getShortName());
            $errorMsg = utilities::replaceStatusMsg($errorMsg, "::ROLENAME::", $args['request']['rolename']);
            $args['flash']->addMessage("error", $errorMsg);

            return;
        }

        $permissionList = $this->processesPermissions($args['request']);

        /*if (empty($permissionList]))
        {
            $errorMsg = errormsg::getErrorMsg("rolenotecreated", (new \ReflectionClass(self::class))->getShortName());
            $errorMsg = utilities::replaceStatusMsg($errorMsg, "::ROLENAME::",  $args['request']['rolename']);
            $args['flash']->addMessage("error", $errorMsg);

            return;
        }*/

        try {

            $role = ['_id' => new MongoID, 'role_name' => $args['request']['rolename'], 'role_permissions' => $permissionList];
            $createRole = new MongoBulkWrite();
            $createRole->insert($role);
            $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->roleCollection, $createRole);

            if ($res->getInsertedCount() == 1) {
                $statusMsg = statusmsg::getStatusMsg("rolecreated", (new \ReflectionClass(self::class))->getShortName());
                $statusMsg = utilities::replaceStatusMsg($statusMsg, "::ROLENAME::", $args['request']['rolename']);

                $args['flash']->addMessage("success", $statusMsg);
            } else {
                $errorMsg = errormsg::getErrorMsg("rolenotecreated", (new \ReflectionClass(self::class))->getShortName());
                $errorMsg = utilities::replaceStatusMsg($errorMsg, "::ROLENAME::", $args['request']['rolename']);
                $args['flash']->addMessage("error", $errorMsg);
            }

            return;
        } catch(ConnectionException $ex) {
            $args['flash']->addMessage("error", $ex->getMessage());
        }
    }

    /**
     * Summary processPermissions - Add all the passed permissions to an array and return it
     * @param $args
     * @return array
     */
    private function processesPermissions($args)
    {
        $permissions = [];

        $sections = settings::ADMIN_SECTIONS;

        foreach($sections as $section)
        {
            $createPerm = $args[$section.'_create'];
            $readPerm = $args[$section.'_read'];
            $updatePerm = $args[$section.'_update'];
            $deletePerm = $args[$section.'_delete'];

            $permissions[$section] = ['create'=>$createPerm, 'read'=>$readPerm, 'update'=>$updatePerm, 'delete'=>$deletePerm];
        }

        return $permissions;
    }
}