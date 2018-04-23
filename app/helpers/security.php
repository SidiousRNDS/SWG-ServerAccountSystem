<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 07 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\utils
 * CLASS: security
 ******************************************************************/

namespace swgAS\helpers;

// Use
// Use
use \MongoDB\Driver\Query as MongoQuery;
use \MongoDB\Driver\BulkWrite;
use \MongoDB\BSON\ObjectId as MongoID;

// Use swgAS
use swgAS\config\settings;
use swgAS\swgAdmin\models\adminroleModel;
use swgAS\swgAdmin\models\adminusersModel;
use swgAS\helpers\userperms;
use swgAS\helpers\sessions;

class security
{
    private $failedLoginCollection = "failedlogin";
    private $lockRanges = [10,15,30]; // These are number of minutes to lock the account
    
    /**
     * Summary loginAttempts - Track the number of login attempts by a helper
     * @param $args
     */
    public function loginAttempts($args)
    {
        $session = new sessions();

        if(isset($_SESSION['flatt'])) {
            $cAttempts = $session->getLoginAttempts();
            $incrementAttempts = $session->increaseLoginAttempts($cAttempts);
            $session->setLoginAttempts($incrementAttempts);
            $newAttempts = $session->getLoginAttempts();

            if ($newAttempts >= 3)
            {
                // Lock Account for 30 minutes
                $this->lockAccount($args);
            }
        }
        else
        {
            $session->setLoginAttempts(1);
        }
    }
    
    /**
     * Summary lockAccount - Lock the IP for a set range of time
     * @param $args
     */
    private function lockAccount($args)
    {
        $checkLocks = $this->checkLocks($args);
      
        if(empty($checkLocks))
        {
            // Create a new lock
            $lockedTill = time() + 60 * $this->lockRanges[0];
            $lockTimer = $this->lockRanges[0];

            $locked = ['_id' => new MongoID, 'lockCount' => 1, 'ip'=>utilities::unobscureData($args['userIP']), 'expire'=>$lockedTill, 'currentLockTimer'=>$lockTimer, 'created_at'=>date('Y-m-d H:i:s'),"updated_at"=>date('Y-m-d H:i:s')];
            $createLock = new BulkWrite();
            $createLock->insert($locked);
            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN.".".$this->failedLoginCollection, $createLock);

            $this->removeSessionLockTracking();

            // Log Locked IP
            $args['adminLockLog']->info("Access Locked for IP: " . utilities::unobscureData($args['userIP']));
        }
        else
        {
            $lockcount = 1; // Default

            // Extend the existing lock to the next level or extend the lock to the max lock time
            if($checkLocks->lockcount >= 3)
            {
                $lockcount = 3;
            }
            else
            {
                $lockcount = $checkLocks->lockcount + 1;
            }

            $lockedTill = time() + 60 * $this->lockRanges[$lockcount];

            $updateLock = new BulkWrite();
            $updateLock->update(
                ['ip'=>utilities::unobscureData($args['userIP'])],
                ['$set' =>['lockCount' => $lockcount, 'expire' => $lockedTill, 'currentlocktime' => $this->lockRanges[$lockcount], 'updated_at' => date('Y-m-d H:i:s')]],
                ['multi'=>false, 'upsert'=>false]
            );
            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN.".".$this->failedLoginCollection, $updateLock);

            $this->removeSessionLockTracking();

            // Log Locked IP
            $args['adminLockLog']->info("Access Locked Updated for IP: " . utilities::unobscureData($args['userIP']));
        }

    }

    /**
     * Summary - Check if there are any locks currently in place for this ip
     * @param $args
     * @return mixed
     */
    public function checkLocks($args)
    {
        $removeLock = $this->removeLock($args);

        if(!empty($removeLock)) {

            return $removeLock;
        }
    }

    /**
     * Summary removeLock - Remove the lock if its past the time limit
     * @param $args
     * @return bool
     */
    private function removeLock($args)
    {
        $checkLockFilter = ['ip' => utilities::unobscureData($args['userIP'])];
        $query = new MongoQuery($checkLockFilter);
        $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->failedLoginCollection,$query);
        $locks = current($res->toArray());

        if(!empty($locks))
        {
            $cTime = time();

            if($cTime > $locks->expire)
            {
                // remove the lock
                $delLock = new BulkWrite();
                $delLock->delete(['ip' => utilities::unobscureData($args['userIP'])]);
                $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN.".".$this->failedLoginCollection, $delLock);

                $this->removeAllLockTracking($args);

                // Log Unlock Lock IP
                $args['adminLockLog']->info("Unlocked Access for IP: " . utilities::unobscureData($args['userIP']));
                //return statusmsg::getStatusMsg("lremoved",(new \ReflectionClass(self::class))->getShortName());
            }
        }

        return $locks;
    }

    /**
     * Summary removeLockTracking - Unset the tracking session and flash messages
     * @param $args
     */
    private function removeAllLockTracking($args)
    {
        unset($_SESSION['flatt']);
        $args['flash']->clearMessages("error");
        $args['flash']->clearMessages("islocked");
    }

    /**
     * Summary removeSessionLockTracking - Unset the session tracking
     */
    private function removeSessionLockTracking()
    {
        unset($_SESSION['flatt']);
    }

    /**
     * Summary addLockMessage - Add lock message to the Flash Message System
     * @param $args
     * @param $msg
     */
    public function addLockMessage($args, $msg)
    {
        $args['flash']->addMessage("error",$msg);
        $args['flash']->addMessage("islocked",true);
    }

    /**
     * Summary loggedInUserRole - Get the perms for the helper that is logged in
     * @param $args
     * @return mixed
     */
    public function loggedInUserRole($args)
    {
        // Get helper Session
        $userSession = new sessions();
        $userSessionId = $userSession->getSession();
        $sessionTable = $userSession->getSessionTable();

        try {
            $session = ['sessionID' => $userSessionId];
            $query = new MongoQuery($session);
            $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$sessionTable,$query);
            $sessionData = current($res->toArray());

            if($sessionData != "") {
                $sessionName = $sessionData->username;
                $user = new adminusersModel();
                $args['username'] = $sessionName;
                $userData = $user->getUserByName($args);

                if($userData !="") {
                    $userRole = $userData->role;
                    $role = new adminroleModel();
                    $args['role_name'] = $userRole;
                    $perms = $role->getRoleByName($args);

                    $_SESSION['role'] = serialize(($userRole));
                    $_SESSION['perms'] = serialize($perms->role_permissions);
                    return $perms->role_permissions;
                }
            }

        } catch (ConnectionException $ex) {
            $args['flash']->addMessageNow("error", $ex->getMessage());
        }



    }
}
