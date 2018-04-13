<?php

namespace swgAS\utils\messaging;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\utils
 * CLASS: errormsg
 ******************************************************************/

class errormsg
{
    protected static $adminLoginErrorMsg = [
    	"notauthorized" => "Access Denied",
		"tomanyattempts" => "IP ::IP:: has been blocked for ::LIMIT:: minutes",
		"invalidsession" => "Invalid Session - Please login"
	];

	/**
	 * Summary of $accountErrorMsg
	 * @var mixed
	 */
	protected static $accountErrorMsg = [
		"unamelong" => "Username is greater then the max length",
		"unameshort" => "Username is shorter then required length",
		"badusername" => "The username you have provided contains illegal characters",
		"authusernomatch" => "Username does not match authorization code username",
		"passlong" => "Password length is greater then the max length",
		"passshort" => "Password is shorter then required length",
		"passnomatch" => "Password do not match",
		"bademail" => "Not a valid email",
		"badip" => "IP not valid",
		"getaccount" => "Could not access the db for getAccount",
		"getaccounts" => "Could not access the db for getAccounts",
		"getaccountemail" => "Could not access the db for getAccountEmail",
		"getaccountusername" => "Could not access the db for getAccountUsername",
		"getaccountip" => "Could not access the db for getAccountIp",
		"validateauthusername" => "Could not access the db for validateAuthUsername",
		"accountexists" => "The account requested already exists",
		"noaccount" => "Account not found",
		"noaccountusername" => "Username not found",
		"noaccountemail" => "Email not found",
		"noaccountip" => "Ip not found",
		"noaccountsavail" => "System has no accounts at this time",
		"tomanyaccounts" => "Account not created you already have the max number of accounts allowed. If you think this is and error please contact the administrator",
		"accountexits" => "The account you are trying to create already exists. If you think this is an error please contact the administrator",
		"authusernomatch" => "The username you provided does not match the authcode user. If you think this is an error please contact the administrator",
		"passvalidation" => "Password could not be validated"
	];

	/**
	 * Summary of $accountbanErrorMsg
	 * @var mixed
	 */
	protected static $accountbanErrorMsg = [];

	/**
	 * Summary of $authcodeErrorMsg
	 * @var mixed
	 */
	protected static $authcodeErrorMsg = [
		"authnotfound" => "Authcode was not found",
		"validateauthusername" => "Could not access the db for validateAuthUsername",
		"getauthid" => "Could not access the db for getAuthCodeId",
		"getauthcodeuser" => "Could not access the db for getAuthCodeUser",
		"validateauth" => "Could not access the db for validateAuth",
		"updateauthcode" => "Could not access the db for updateAuthCode",
		"getactiveauthcodes" => "Could not access the db for getActiveAuthcodes",
		"authnotgenerated" => "No authcode created account already exists - Please change the username and/or email to generate an authcode"
	];

	/**
	 * Summary of $characterbanErrorMsg
	 * @var mixed
	 */
	protected static $characterbanErrorMsg = [];

	/**
	 * Summary of $galaxybanErrorMsg
	 * @var mixed
	 */
	protected static $galaxybanErrorMsg = [];

	protected static $passwordErrorMsg = [
		"salt" => "No salt was generated"
	];

	protected static $roleErrorMsg = [
		"rolenotcreated" => "Role ::ROLENAME:: was not created."
	];

	/**
	 * Summary of getErrorMsg
	 * @param string $code
	 * @param string $model
	 * @return string
	 */
	public static function getErrorMsg(string $code, string $model ) : string
	{
		$error = null;

		if ($code != "") {
			switch($model)
			{
				case "accountModel":
					$error = self::$accountErrorMsg[$code];
					break;
				case "accountbanModel":
					$error = self::$accountbanErrorMsg[$code];
					break;
				case "authcodeModel":
					$error = self::$authcodeErrorMsg[$code];
					break;
				case "characterbanModel":
					$error = self::$characterbanErrorMsg[$code];
					break;
				case "galaxybanModel":
					$error = self::$galaxybanErrorMsg[$code];
					break;
				case "password":
					$error = self::$passwordErrorMsg[$code];
					break;
				case "adminloginModel":
				case "adminauthmiddleware":
					$error = self::$adminLoginErrorMsg[$code];
					break;
                case "adminroleModel":
                    $error = self::$roleErrorMsg[$code];
                    break;
			}
		}

		return $error;
	}
}

?>
