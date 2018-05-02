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
use \MongoDB\Driver\Query as MongoQuery;

// Use swgAS
use swgAS\config\settings;
use swgAS\helpers\utilities;
use swgAS\helpers\messaging\errormsg;
use swgAS\helpers\messaging\statusmsg;

class adminroleModel
{
    private $roleCollection = "user_roles";


    /**
     * Summary getRoleByName
     * @param $args
     * @return string
     */
    public function getRoleByName($args)
    {
        try {
            $role = ['role_name' =>$args['role_name']];
            $query = new MongoQuery($role);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->roleCollection,$query);
            $roleData = current($res->toArray());
            $mongoEntryChecked = $this->checkSections($roleData);

            return $mongoEntryChecked;

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * Summary getRoles - Get all the roles in the user_roles collection
     * @param $args
     * @return \MongoCursor
     * @throws \Exception
     */
    public function getRole($args)
    {
        try {
            $role = ['_id' => new MongoID($args['id'])];
            $query = new MongoQuery($role);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->roleCollection,$query);
            $roleData = current($res->toArray());
            $mongoEntryChecked = $this->checkSections($roleData);

            return json_encode($mongoEntryChecked);

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }


    /**
     * Summary checkSections - check to see if there were have been new sections added to the system and if so
     * then make sure we add them to the permissions object so that we can set them
     * @param $roles
     * @return mixed
     */
    private function checkSections($roles)
    {
        $missing = [];

        foreach(settings::ADMIN_SECTIONS as $section) {
            if (!utilities::in_array_r($section, $roles)) {
                array_push($missing,$section);
            }
        }

        if($missing) {
            if(is_object($roles))
            {
                for ($x = 0; $x < count($missing); $x++) {
                    $section = $missing[$x];
                    $roles->role_permissions->$section = (object) ['create' => null, 'read' => null, 'update' => null, 'delete' => null];
                }
            }
            else {
                foreach ($roles as $role) {
                    for ($x = 0; $x < count($missing); $x++) {
                        $section = $missing[$x];
                        $role->role_permissions->$section = ['create' => null, 'read' => null, 'update' => null, 'delete' => null];
                    }
                }
            }
        }

        return $roles;
    }

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

            $mongoEntryChecked = $this->checkSections($mongoCursor->toArray());

            return json_encode($mongoEntryChecked);

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
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
            $errorMsg = errormsg::getErrorMsg("rolenotcreated", (new \ReflectionClass(self::class))->getShortName());
            $errorMsg = utilities::replaceStatusMsg($errorMsg, "::ROLENAME::", $args['request']['rolename']);
            $args['flash']->addMessageNow("error", $errorMsg);

            return;
        }

        $permissionList = $this->processesPermissions($args['request']);

        try {

            $role = ['_id' => new MongoID, 'role_name' => $args['request']['rolename'], 'role_permissions' => $permissionList];
            $createRole = new MongoBulkWrite();
            $createRole->insert($role);
            $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->roleCollection, $createRole);

            if ($res->getInsertedCount() == 1) {
                $statusMsg = statusmsg::getStatusMsg("rolecreated", (new \ReflectionClass(self::class))->getShortName());
                $statusMsg = utilities::replaceStatusMsg($statusMsg, "::ROLENAME::", $args['request']['rolename']);

                $args['flash']->addMessageNow("success", $statusMsg);
            } else {
                $errorMsg = errormsg::getErrorMsg("rolenotecreated", (new \ReflectionClass(self::class))->getShortName());
                $errorMsg = utilities::replaceStatusMsg($errorMsg, "::ROLENAME::", $args['request']['rolename']);
                $args['flash']->addMessageNow("error", $errorMsg);
            }

            return;
        } catch(ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * Summary updateRole - Update selected role
     * @param $args
     * @throws \ReflectionException
     */
    public function updateRole($args)
    {
        try {
            $permissionList = $this->processesPermissions($args['request']);
            $updateRole = new MongoBulkWrite();
            $updateRole->update(
                ['_id' => new MongoID($args['request']['id'])],
                ['$set' => ['role_name' => $args['request']['rolename'], 'role_permissions' => $permissionList]],
                ['multi' => false, 'upsert' => false]
            );

            $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->roleCollection, $updateRole);

            $statusMsg = statusmsg::getStatusMsg("roleupdated", (new \ReflectionClass(self::class))->getShortName());
            $statusMsg = utilities::replaceStatusMsg($statusMsg, "::ROLENAME::", $args['request']['rolename']);

            $args['flash']->addMessageNow("success", $statusMsg);
        } catch(ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }
    }

    /**
     * Summary deleteRole - Remove selected role from the DB
     * @param $args
     * @throws \ReflectionException
     */
    public function deleteRole($args)
    {
        $roleData = json_decode($this->getRole($args));
        $roleName = $roleData->role_name;


        try {
            $deleteRole = new MongoBulkWrite();
            $deleteRole->delete(['_id' => new MongoID($args['id'])], ['limit' => 1]);

            $res = $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN . "." . $this->roleCollection, $deleteRole);

            $statusMsg = statusmsg::getStatusMsg("roledeleted", (new \ReflectionClass(self::class))->getShortName());
            $statusMsg = utilities::replaceStatusMsg($statusMsg, "::ROLENAME::", $roleName);

            $args['flash']->addMessage("success", $statusMsg);

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
            $createPerm = (isset($args[$section.'_create']) ? $args[$section.'_create'] : null);
            $readPerm = (isset($args[$section.'_read']) ? $args[$section.'_read'] : null);
            $updatePerm = (isset($args[$section.'_update']) ? $args[$section.'_update'] : null);
            $deletePerm = (isset($args[$section.'_delete']) ? $args[$section.'_delete'] : null);

            $permissions[$section] = ['create'=>$createPerm, 'read'=>$readPerm, 'update'=>$updatePerm, 'delete'=>$deletePerm];
        }

        return $permissions;
    }
}