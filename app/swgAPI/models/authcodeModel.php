<?php

namespace swgAS\swgAPI\models;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\models
 * CLASS: authcodeModel
 ******************************************************************/

// Use

// swgAS Use
use swgAS\config\settings;
use swgAS\swgAPI\utils\validation;
use swgAS\swgAPI\utils\errormsg;

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
	 * @param mixed $args
	 * @throws \Error
	 * @return mixed
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

	/**
	 * Summary of getAuthcodeUser
	 * @param array $args
	 * @throws \Error
	 * @return mixed
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
	 * @throws \Error
	 * @return mixed
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
	 * @return boolean
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
	 * @param mixed $args
	 * @return \null|string
	 */
	public static function createAuthCode($args)
	{
		return self::buildAuthCode($args);
	}

	/**
	 * Summary of generateAuthCode
	 * @return \null|string
	 */
	private static function buildAuthCode($args)
	{
		$authCode = null;

		if($args['prefix'] === settings::MAIN_CODE_PREFIX)
		{
			$authCode = settings::MAIN_CODE_PREFIX;
		}

		if($args['prefix'] === settings::EXTENDED_CODE_PREFIX)
		{
			$authCode = settings::EXTENDED_CODE_PREFIX;
		}

		$primarySection = self::generateAuthCodeSections(settings::CODE_LENGTH_PRIMARY);

		$authCode = $authCode . "-". $primarySection;

		if(settings::USE_SECONDARY)
		{
			$secondarySection = self::generateAuthCodeSections(settings::CODE_LENGTH_SECONDARY);
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
	private function buildAuthCodeSections(int $sectionLength)
	{
		$section = null;

		for($i=0; $i < $sectionLength; $i++)
		{
			$section .= settings::CODE_CHARS[rand(0,strlen(settings::CODE_CHARS) - 1)];
		}

		return $section;
	}
}

?>
