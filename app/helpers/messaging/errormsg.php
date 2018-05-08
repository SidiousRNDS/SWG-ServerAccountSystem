<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\utils
 * CLASS: errormsg
 ******************************************************************/

namespace swgAS\helpers\messaging;

/**
 * Class errormsg
 * @package swgAS\helpers\messaging
 */
class errormsg
{
    /**
     * @var array
     */
    protected static $adminLoginErrorMsg = [
    	"notauthorized" => "Access Denied",
		"tomanyattempts" => "IP ::IP:: has been blocked for ::LIMIT:: minutes",
		"invalidsession" => "Invalid Session - Please login"
	];

    /**
     * @var array
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
		"authusernomatch" => "The username you provided does not match the authcode helper. If you think this is an error please contact the administrator",
		"passvalidation" => "Password could not be validated"
	];

    /**
     * @var array
     */
	protected static $accountbanErrorMsg = [];

    /**
     * @var array
     */
	protected static $authcodeErrorMsg = [
		"authnotfound" => "Authcode was not found",
		"validateauthusername" => "Could not access the db for validateAuthUsername",
		"getauthid" => "Could not access the db for getAuthCodeId",
		"getauthcodeuser" => "Could not access the db for getAuthCodeUser",
		"validateauth" => "Could not access the db for validateAuth",
		"updateauthcode" => "Could not access the db for updateAuthCode",
		"getactiveauthcodes" => "Could not access the db for getActiveAuthcodes",
		"authnotgenerated" => "No authcode created account already exists - Please change the username and/or email to generate an authcode",
		"codechars" => "Code chars were not set in the settings file in config",
		"issuegeneratingauthcode" => "There was an issue generation the authcode it either did not match the length or there is a bigger issue"
	];

    /**
     * @var array
     */
	protected static $characterbanErrorMsg = [];

    /**
     * @var array
     */
	protected static $galaxybanErrorMsg = [];

    /**
     * @var array
     */
	protected static $passwordErrorMsg = [
		"salt" => "No salt was generated"
	];

    /**
     * @var array
     */
	protected static $roleErrorMsg = [
		"rolenotcreated" => "Role ::ROLENAME:: was not created."
	];

    /**
     * @var array
     */
	protected static $userErrorMsg = [
		"usernotcreatedmissing" => "User was not created because its missing ::MISSING::",
		"usernotcreatednomatch" => "User was not created because password did not match",
		"usernotcreatednorole" => "User was not created because no role was selected",
		"usernotcreatednoemail" => "User was not created because no email was provided",
		"usernotcreated" => "User ::USERNAME:: was not created"
	];

    /**
     * @var array
     */
	protected static $patchErrorMsg = [
		"patchmissingtitle" => "The patch you are trying to create is missing a title this is required to create a patch.",
		"patchmissingnotes" => "The patch you are trying to create is missing notes this is required to create a patch",
		"patchmissingserver" => "The patch you are trying to create is missing the server it is to be applied to",
        "patchalreadyexists" => "Server patch ::PATCHNAME:: already exists. Please choose a different name.",
		"serverpatchnotcreated" => "Server patch ::PATCHNAME:: was not created",
		"liveconfigfailed" => "Server patch ::PATCHNAME:: was unable to update the live.cfg file",
		"uploadfailed" => "File upload failed."
	];

    /**
	 * @method getErrorMsg
	 * Get the requested error message and return it to the calling method
     * @param string $code
     * @param string $model
     * @return string
     */
	public static function getErrorMsg(string $code, string $model ) : string
	{
		
		$error = "unknown";

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
				case "processauthcodes":
				case "adminauthcodeModel":
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
				case "adminusersModel":
					$error = self::$userErrorMsg[$code];
					break;
				case "adminserverupdatesModel":
				case "admingameupdatesutilsModel":
					$error = self::$patchErrorMsg[$code];
					break;
			}
		}

		return $error;
	}
}
