<?php
namespace swgAPI\utils;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\utils
 * CLASS: errormsg
 ******************************************************************/

// Use

// swgAPI Use
use swgAPI\config\settings;
use swgAPI\models\authcodeModel as authcode;
use swgAPI\models\accountModel as account;

class validation
{
	/**
	 * Summary of validateAccount
	 * @param mixed $args
	 * @return \boolean|null|string
	 */
	public static function validateAccount($args)
	{
		$valid = true;

		$uValid = self::validateUsername($args);
		if ($uValid != null)
		{
			return $uValid;
		}

		$pValid = self::validatePassword($args);
		if ($pValid != null)
		{
			return $pValid;
		}

		$eValid = self::validateEmail($args);
		if ($eValid != null)
		{
			return $eValid;
		}

		$iValid = self::validateIp($args);
		if ($iValid != null)
		{
			return $iValid;
		}

		return $valid;
	}

	/**
	 * Summary of validateUsername
	 * @param array $args
	 * @return \null|string
	 */
	private static function validateUsername(array $args)
	{
		// Check to see if the username is greater then the max length
		if(strlen($args['username']) > account::$usernameMaxLength)
		{
			return errormsg::getErrorMsg("unamelong", 'accountModel');
		}

		// Check to see if the username is less then the min length
		if(strlen($args['username']) <  account::$usernameMinLength)
		{
			return errormsg::getErrorMsg("unameshort", 'accountModel');
		}

		// Check to see if the username matches the required params
		if(!preg_match( account::$usernameRegEx, $args['username']))
		{
			return errormsg::getErrorMsg("badusername", 'accountModel');
		}

		// Check to see if the username matches the username for the authMsg
		if(!validation::validateAuthUsername($args))
		{
			return errormsg::getErrorMsg("authusernomatch", 'accountModel');
		}

		return null;
	}

	/**
	 * Summary of validatePassword
	 * @param array $args
	 * @return \null|string
	 */
	private static function validatePassword(array $args)
	{
		// Check to see if the password is greater then the max length
		if(strlen($args['password']) > account::$passwordMaxLength)
		{
			return errormsg::getErrorMsg("passlong", 'accountModel');
		}

		// Check to see if the password is less then the min length
		if(strlen($args['password']) < account::$passwordMinLength)
		{
			return errormsg::getErrorMsg("passshort", 'accountModel');
		}

		// Check to see if the password matches the respassword
		if($args['password'] != $args['repassword'])
		{
			return errormsg::getErrorMsg("passnomatch", 'accountModel');
		}

		return null;
	}

	/**
	 * Summary of validateEmail
	 * @param array $args
	 * @return \null|string
	 */
	private static function validateEmail(array $args)
	{
		// Check to see if we have a valid email format
		if(!filter_var($args['email'], FILTER_VALIDATE_EMAIL))
		{
			return errormsg::getErrorMsg("bademail", 'accountModel');
		}

		return null;
	}

	/**
	 * Summary of validateIp
	 * @param array $args
	 * @return \null|string
	 */
	private static function validateIp(array $args)
	{
		// Check to see if we have a valid IP format
		if(!filter_var($args['ip'], FILTER_VALIDATE_IP))
		{
			return errormsg::getErrorMsg("badip",'accountModel');
		}

		return null;

	}


	/**
	 * Summary of validateEncryptedPassword
	 * Used for Unit Test
	 * @param mixed $args
	 * @return boolean
	 */
	public static function validateEncryptedPassword($args)
	{
		$testPassHash = hash(settings::CRYPTHASH, settings::PWSECRET.$args['password'].$args['salt']);

		if($testPassHash === $args['passwordHash'])
		{
			return true;
		}

		return false;
	}

	/**
	 * Summary of validateAuthCode
	 * @param array $args
	 * @return mixed
	 */
	public static function validateAuthCode(array $args)
	{
		return authcode::validateAuth($args);
	}

	/**
	 * Summary of validateAuthUsername
	 * @param string $username
	 * @throws \Exception
	 * @return mixed
	 */
	public static function validateAuthUsername(array $args)
	{
		return authcode::getAuthcodeUser($args);
	}

	/**
	 * Summary of validateAuthCodeLength
	 * @param mixed $authCode
	 * @return boolean
	 */
	public function validateAuthCodeLength($authCode)
	{
		$totalLength = 0;
		$mainPrefixLen = strlen(settings::MAIN_CODE_PREFIX);
		$extendPrefixLen = strlen(settings::EXTENDED_CODE_PREFIX);
		$primarySectionLen = settings::CODE_LENGTH_PRIMARY;
		$secondarySectionLen = settings::CODE_LENGTH_SECONDARY;

		$authCodeLen = strlen($authCode);

		if(settings::USE_SECONDARY)
		{
			if(settings::DIVIDERS)
			{
				$totalLength = 2;
			}

			if(preg_match("/".settings::MAIN_CODE_PREFIX."/",$authCode))
			{
				$totalLength = $totalLength + $mainPrefixLen + $primarySectionLen + $secondarySectionLen;

				if($authCodeLen === $totalLength)
				{
					return true;
				}
			}
			else
			{
				$totalLength = $totalLength + $extendPrefixLen + $primarySectionLen + $secondarySectionLen;

				if($authCodeLen === $totalLength)
				{
					return true;
				}
			}
		}
		else
		{
			if(settings::DIVIDERS)
			{
				$totalLength = 1;
			}

			if(preg_match("/".settings::MAIN_CODE_PREFIX."/",$authCode))
			{
				$totalLength = $totalLength + $mainPrefixLen + $primarySectionLen;

				if($authCodeLen === $totalLength)
				{
					return true;
				}
			}
			else
			{
				$totalLength = $totalLength + $extendPrefixLen + $primarySectionLen;

				if($authCodeLen === $totalLength)
				{
					return true;
				}
			}
		}
		return false;
	}
}

?>