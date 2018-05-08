<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\models
 * CLASS: authcodeModel
 ******************************************************************/

namespace swgAS\swgAPI\models;

// Use

// swgAS Use
use swgAS\config\settings;
use swgAS\helpers\validation;
use swgAS\helpers\messaging\errormsg;
use swgAS\helpers\messaging\statusmsg;
use swgAS\helpers\utilities;

use swgAS\swgAPI\models\accountModel;

/**
 * Summary of authcodeModel
 */
class authcodeModel extends \Illuminate\Database\Eloquent\Model
{
	/**
	 * Summary of $accountsTable
	 * @var string
	 */
	protected static $authTable = "admin_auth_codes";

    /**
	 * Summary of getAuthCodeId
     * @param $args
     * @return string
     * @throws \ReflectionException
     */
	public static function getAuthCodeId($args)
	{
		try {
			$results = $args['db']::table(self::$authTable)
					->select('aaid')
					->where('username', '=', $args['username'])
					->where(function($query) {
						$query->where('auth_code_used', '!=', 1);
					})
					->first();

			if ($results === null)
			{
				return errormsg::getErrorMsg("authnotfound", (new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		}
		catch (Error $ex) {
			throw new \Error (errormsg::getErrorMsg("getauthid", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

	public static function getAuthcodeById($args)
    {
        try {
            $results = $args['db']::table(self::$authTable)
                ->select('')
                ->where('username', '=', $args['username'])
                ->where(function($query) {
                    $query->where('auth_code_used', '!=', 1);
                })
                ->first();

            if ($results === null)
            {
                return errormsg::getErrorMsg("authnotfound", (new \ReflectionClass(self::class))->getShortName());
            }

            return $results;

        }
        catch (Error $ex) {
            throw new \Error (errormsg::getErrorMsg("getauthid", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
        }
    }

    /**
	 * Summary of getAuthcodeUser
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	public static function getAuthcodeUser(array $args)
	{
		try {
			$results = $args['db']::table(self::$authTable)
					->select('username')
					->where('username', '=', $args['username'])
					->where(function($query) {
						$query->where('auth_code_used', '!=', 1);
					})
					->first();

			if ($results === null)
			{
				return errormsg::getErrorMsg("authnotfound", (new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		}
		catch (Error $ex) {
			throw new \Error (errormsg::getErrorMsg("getauthcodeuser", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
	 * Summary of validateAuthcode
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	public static function validateAuth(array $args)
	{
		try {
			$results = $args['db']::table(self::$authTable)
					->select('aaid')
					->where('auth_code', '=', $args['authcode'])
					->where(function($query) {
						$query->where('auth_code_used', '!=', 1);
					})
					->first();

			if ($results === null)
			{
				return errormsg::getErrorMsg("authnotfound", (new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		}
		catch (Error $ex) {
			throw new \Error (errormsg::getErrorMsg("validateauth", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
	 * Summary of updateAuthcode
     * @param array $args
     * @return bool
     * @throws \ReflectionException
     */
	public static function updateAuthcode(array $args)
	{
		try {
			$result = $args['db']::table(self::$authTable)
					->where('username', '=', $args['username'])
					->update(['auth_code_used' => 1]);

			if($result !== false)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (Error $ex) {
			throw new \Error (errormsg::getErrorMsg("updateauthcode", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
	 * Summary of createAuthCode
     * @param $args
     * @throws \ReflectionException
     */
	public static function createAuthCode($args)
	{
		$user = accountModel::checkUsername($args);


		if($user == errormsg::getErrorMsg("noaccount",'accountModel'))
		{
            $checkAuthUsername = self::checkUsername($args);


            if($checkAuthUsername == "") {
                $authCode = self::buildAuthCode($args);

                $args['authcode'] = $authCode;

                $authCodeEntered = self::addAuthCode($args);

                if ($authCodeEntered != "") {
                    $args['flash']->addMessage("error", "Issue with the db authcode was not entered");
                    return;
                }

                // Add Authcode to the DB
                if ($authCode != "") {
                    $statusMsg = statusmsg::getStatusMsg("authcreated", (new \ReflectionClass(self::class))->getShortName());
                    $statusMsg = utilities::replaceStatusMsg($statusMsg, "::USERNAME::", $args['username']);
                    $statusMsg = utilities::replaceStatusMsg($statusMsg, "::AUTHCODE::", $authCode);


                    $args['flash']->addMessage("success", $statusMsg);
                } else {
                    $args['flash']->addMessage("error", "shit went south.");
                }
            }
            else
			{
                $args['flash']->addMessage("error",errormsg::getErrorMsg("authnotgenerated", (new \ReflectionClass(self::class))->getShortName()));
			}

		}
		else {
            $args['flash']->addMessage("error",errormsg::getErrorMsg("authnotgenerated", (new \ReflectionClass(self::class))->getShortName()));
		}
	}

    /**
	 * Summary checkUsername
     * @param $args
     * @return string
     */
	private static function checkUsername($args)
	{
        try {
            $addAuthcode = $args['db']::table(self::$authTable)
                ->select('aaid')
                ->where('username', '=', $args['username'])
				->orWhere('email', '=', $args['email'])
				->first();

            return $addAuthcode;


        } catch (Error $ex) {
            return $ex->getMessage();
        }
	}

    /**
	 * Summary addAuthCode
     * @param $args
     * @return string
     */
	private static function addAuthCode($args)
	{
		try {
            $addAuthcode = $args['db']::table(self::$authTable)->insert([
                "username" => $args["username"],
                "email" => $args["email"],
                "auth_code" => $args['authcode']
            ]);
        } catch (Error $ex) {
			return $ex->getMessage();
		}
	}

	/**
	 * Summary of generateAuthCode
	 * @return \null|string
	 */
	private static function buildAuthCode($args)
	{
		$authCode = null;

		if($args['prefix'] === settings::PRIMARY_CODE_PREFIX)
		{
			$authCode = settings::PRIMARY_CODE_PREFIX;
		}

		if($args['prefix'] === settings::EXTENDED_CODE_PREFIX)
		{
			$authCode = settings::EXTENDED_CODE_PREFIX;
		}

		$primarySection = self::buildAuthCodeSections(settings::CODE_LENGTH_PRIMARY);

		$authCode = $authCode . "-". $primarySection;

		if(settings::USE_SECONDARY)
		{
			$secondarySection = self::buildAuthCodeSections(settings::CODE_LENGTH_SECONDARY);
			$authCode = $authCode . "-". $secondarySection;
		}

		// Remove dividers if the const is set to false
		if(settings::DIVIDERS === false)
		{
			$authCode = preg_replace("/-/","",$authCode);
		}

		$checkCode = validation::validateAuthCodeLength($authCode);

		if($checkCode === true)
		{
			return $authCode;
		}

		return null;
	}

	/**
	 * Summary of generateAuthCodeSections
	 * @param int $sectionLength
	 * @return \null|string
	 */
	private static function buildAuthCodeSections(int $sectionLength)
	{
		$section = null;

		for($i=0; $i < $sectionLength; $i++)
		{
			$section .= settings::CODE_CHARS[rand(0,strlen(settings::CODE_CHARS) - 1)];
		}

		return $section;
	}

    /**
	 * Summary of getActiveAuthcodes - Get a list of authcodes that have not been used yet
     * @param $args
     * @return int
     * @throws \ReflectionException
     */
	public static function getActiveAuthcodes($args)
	{
        try {
            $result = $args['db']::table(self::$authTable)
				->select('aaid','username', 'email', 'created_at', 'auth_code')
                ->where('auth_code_used', '!=', 1)
            	->get();

                return $result;
        }
        catch (Error $ex) {
            throw new \Error (errormsg::getErrorMsg("getactiveauthcodes", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
        }
	}

    /**
	 * Summary getUsedAuthcodes
     * @param $args
     * @return mixed
     */
	public static function getUsedAuthcodes($args)
	{
        $results = $args['db']::table(self::$authTable)
            ->select('aaid','username','email','created_at','used_date')
            ->where('auth_code_used', '=', 1)
            ->get();
            
        return $results;
	}
}

?>
