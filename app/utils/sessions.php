<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 06 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\utils
 * CLASS: sessions
 ******************************************************************/

namespace swgAS\utils;

// Use
use \MongoDB\Driver\Query as MongoQuery;
use \MongoDB\Driver\BulkWrite;
use \MongoDB\Driver\Exception\Exception as MongoExpception;
use \MongoDB\BSON\ObjectId as MongoID;

// Use swgAS
use swgAS\config\settings;

class sessions
{
    private $sessionLength = 15;
    private $sessionsCollection = "users_sessions";

    public function checkValidUserSession($args)
    {
        $sessionData = $this->checkUserSession($args);
        $args['sessiontimestamp'] = $sessionData->expire;

        $sessionExpired = $this->checkSessionIsExpired($args);

        return $sessionExpired;
    }

    /**
     * Summary setUserSession
     * @param $args
     * @throws \Exception
     */
    public function setUserSession($args)
    {
        return $this->generateUserSession($args);
    }

    /**
     * Summary generateUserSession - Check for current session or generate a new one if there is not one or its expired
     * @param $args
     * @throws \Exception
     */
    private function generateUserSession($args)
    {
        if(isset($_SESSION['swgASA']))
        {
            $args['sessionID'] = $_SESSION['swgASA'];

            $sessionData = $this->checkUserSession($args);

            $args['sessiontimestamp'] = $sessionData->expire;

            $sessionExpired = $this->checkSessionIsExpired($args);

            if($sessionExpired === true) {
                // Remove entry from the db
                $this->removeSessionById($args);
                // Unset the session
                unset($_SESSION['swgASA']);
                // Generate new session
                $this->generateUserSession($args);
            }
        }
        else
        {
            // Remove Old Session if there are any in the DB
            $this->removeSessionByUser($args);

            // Add mew session to the DB
            $sessionID = $this->generateSessionID();
            $sessionExpire = time() + 60*60;    // Expire in 1 hour

            $session = ['_id' => new MongoID, 'sessionID' => $sessionID, 'username'=>$args['username'], 'expire'=>$sessionExpire, 'setat'=>time(), 'created_at'=>date('Y-m-d H:i:s')];

            $createSession = new BulkWrite;
            $createSession->insert($session);
            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN.".".$this->sessionsCollection, $createSession);

            $_SESSION['swgASA'] = $sessionID;
        }
    }

    /**
     * Summary checkUserSession - Check to see if the user has a session stored and if so return the data
     * if the user does not return null
     * @param $args
     * @return mixed
     */
    private function checkUserSession($args)
    {

        $sessionFilter = ['sessionID'=>$args['sessionID']];

        $query = new MongoQuery($sessionFilter);
        $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->sessionsCollection,$query);
        $sessionData = current($res->toArray());

        return $sessionData;
    }

    /**
     * Summary checkSessionIsExpired - Check to see if the session timestamp that was passed in is behind the current timestamp
     * if so then the session is expired and return true else return false
     * @param $args
     * @return bool
     */
    private function checkSessionIsExpired($args)
    {
        $cDate = new \DateTime();
        $currentTimeStamp = $cDate->getTimestamp();

        if($args['sessiontimestamp'] <= $currentTimeStamp)
        {
            unset($_SESSION['swgASA']);
            return true;
        }

        return false;
    }

    /**
     * Summary removeSessionById - Remove the existing session from the db
     * @param $args
     */
    private function removeSessionById($args)
    {
        try {
            $delSession = new BulkWrite;
            $delSession->delete(['sessionID' => $args['sessionID'], ['limit' => 1]]);
            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN.".".$this->sessionsCollection, $delSession);
        } catch (MongoExpception $e){
            throw new MongoException($e->getMessage());
        }
    }

    /**
     * Summary removeSessionByUsername - Remove the existing session from the db
     * @param $args
     */
    private function removeSessionByUser($args)
    {
        try {
            $delSession = new BulkWrite;
            $delSession->delete(['username' => $args['username']]);
            $args['mongodb']->executeBulkWrite(settings::MONGO_ADMIN.".".$this->sessionsCollection, $delSession);
        } catch (MongoExpception $e){
            throw new MongoException($e->getMessage());
        }
    }

    /**
     * Summary generateSessionID - Genereate a random session ID
     * @return bool|string
     * @throws \Exception
     */
    private function generateSessionID()
    {
        $sessionID = 0;

        if (function_exists("random_bytes")) {
            $sessionID = random_bytes(ceil($this->sessionLength / 2));
        }
        elseif (function_exists("openssl_random_pseudo_bytes"))
        {
            $sessionID = openssl_random_pseudo_bytes($this->sessionLength / 2);
        }
        else
        {
            throw new \Exception("No crypto secure random method found");
        }

        return substr(bin2hex($sessionID), 0, $this->sessionLength);
    }

    /**
     * Summary setLoginAttempts - This sets a session var to track the number of times a user has tried to login and failed
     * @param $args
     */
    public function setLoginAttempts($attempts)
    {
        if($attempts === "") {
            // Tracking failed login attempts
            $_SESSION['flatt'] = 1;
        }
        else {
            $_SESSION['flatt'] = $attempts;
        }
    }

    /**
     * Summary getLoginAttempts - This gets the data in the Session for the number of times a user has tried to login and failed
     * @return mixed
     */
    public function getLoginAttempts()
    {
        return $_SESSION['flatt'];
    }

    /**
     * Summary increaseLoginAttempts - This increments the number of failed attempts in the Session var
     */
    public function increaseLoginAttempts($attempts)
    {
        return $attempts + 1;
    }

    /**
     * Summary setSessionLocked - This adds the session that we check for to see if the user is locked out
     */
    public function setSessionLocked()
    {
        $_SESSION['aslockat'] = time();
    }
}