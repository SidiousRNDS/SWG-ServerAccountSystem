<?php

namespace swgAS\swgAPI\models;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\models
 * CLASS: accountModel
 ******************************************************************/

// Use
use \Illuminate\Database\Eloquent\Model as Model;

// swgAS Use
use swgAS\config\settings;
use swgAS\helpers\messaging\errormsg;
use swgAS\helpers\messaging\statusmsg;
use swgAS\helpers\validation;
use swgAS\helpers\password;
use swgAS\helpers\station;
use swgAS\helpers\utilities;

/**
 * Summary of accountModel
 */
class accountModel extends Model
{
	/**
	 * Summary of $accountsTable
	 * @var string
	 */
	protected static $accountsTable = "accounts";

	/**
	 * Summary of $usernameMaxLength
	 * @var int
	 */
	public static $usernameMaxLength = 32;

	/**
	 * Summary of $usernameMinLength
	 * @var int
	 */
	public static $usernameMinLength = 4;

	/**
	 * Summary of $usernameRegEx
	 * @var mixed
	 */
	public static $usernameRegEx = "/^[a-zA-Z0-9@.\-]*$/";

	/**
	 * Summary of $passwordMaxLength
	 * @var int
	 */
	public static $passwordMaxLength = 32;

	/**
	 * Summary of $passwordMinLength
	 * @var int
	 */
	public static $passwordMinLength = 5;

	/**
	 * Pubic Methods
	 */

	/**
	 * Summary of getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return self::$accountsTable;
	}

    /**
	 * Summary - checkUsername check to see if a username already exists in the system
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	public static function checkUsername(array $args)
	{
        try {

            $results = $args['db']::table(self::$accountsTable)
                ->select('account_id')
                ->where('username', '=', $args['username'])
                ->first();

            if ($results === null)
            {
                return errormsg::getErrorMsg("noaccount",(new \ReflectionClass(self::class))->getShortName());
            }

            return $results;

        } catch (Error $ex) {
            $args['errorLogger']->error('accountModel::getAccount',array("error"=>new \ReflectionClass(self::class))->getShortName() . " " . $ex->getMessage());
			throw new \Error (errormsg::getErrorMsg("getaccount",(new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	public static function getAccount(array $args)
	{
		try {

			$results = $args['db']::table(self::$accountsTable)
					->select('account_id')
					->where('username', '=', $args['username'])
					->orWhere('email', '=', $args['email'])
					->orWhere('create_ip', '=', $args['ip'])
					->first();

			if ($results === null)
			{
				return errormsg::getErrorMsg("noaccount",(new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		} catch (Error $ex) {
			$args['errorLogger']->error('accountModel::getAccount',array("error"=>new \ReflectionClass(self::class))->getShortName() . " " . $ex->getMessage());
			throw new \Error (errormsg::getErrorMsg("getaccount",(new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	public static function getAccounts(array $args)
	{
		try {

			$results = $args['db']::table(self::$accountsTable)
					->select('account_id','created')
					->get();

			if ($results === null)
			{
				return errormsg::getErrorMsg("noaccountsavail", (new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		}
		catch (Error $ex) {
			$args['errorLogger']->error('accountModel::getAccounts',array("error"=>new \ReflectionClass(self::class))->getShortName() . " " . $ex->getMessage());
			throw new \Error (errormsg::getErrorMsg("getaccounts", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
     * @param array $args
     * @return array|bool|mixed|null|string
     * @throws \ReflectionException
     */
	public static function addAccount(array $args)
	{
		$validate = validation::validateAccount($args);

		if($validate === true)
		{
			// Check to see if the username is already used
			$uCheck = self::getAccountUsername($args);

			// Check to see if we have the email listed in our system already
			$eCheck = self::getAccountEmail($args);

			// Check to see if we have the ip listed in our system already
			$iCheck = self::getAccountIp($args);

			// Check to see if we have a valid auth code
			$aCheck = validation::validateAuthCode($args);

			$processAccountRequest = self::processNewAccountData(["helper"=>$uCheck, "email"=>$eCheck, "ip"=>$iCheck, "authcode"=>$aCheck, "username"=>$args['username']]);

			if($processAccountRequest === statusmsg::getStatusMsg("checkspassed", (new \ReflectionClass(self::class))->getShortName()))
			{
				// Generate Station ID
				$station = new station();
				$station_id = $station->generateStationId($args);
				$args['station_id'] = $station_id;

				// Generate Encrypted Password and Salt
				$password = new password();

				$args['salt'] = $password->getSalt();

				$passData = $password->generateEncryptedPassword($args);

				$args['passwordHash'] = $passData['passwordHash'];
				$args['salt'] = $passData['salt'];

				// Validate Password
				$passValidation = validation::validateEncryptedPassword($args);

				if($passValidation === false)
				{
					return errormsg::getErrorMsg("passvalidation", (new \ReflectionClass(self::class))->getShortName());
				}

				// Insert new account
				$addResults = $args['db']::table(self::$accountsTable)->insert([
					"username" => $args["username"],
					"password" => $passData['passwordHash'],
					"station_id" => $args["station_id"],
					"active" => 1,
					"admin_level" => 0,
					"salt" => $passData["salt"],
					"email" => $args["email"],
					"create_ip" => $args['ip'],
					"admin_auth" => 1
				]);

				if($addResults !== false)
				{
					// Log helper was created
					//$args['apiLogger']->info('APITOKEN Given',array("clientIP"=>$client_ip,"Token"=>$token, "Expires"=>$future->getTimestamp()));
					$args['userLogger']->info('USER: ' . $args['username'] .' has been created');

					// Update the Authcode to used
					$authUpdate = authcodeModel::updateAuthcode($args);

					if($authUpdate === false)
					{
						// Log authcode was not updated
						$args['errorLogger']->error('accountModel::addAccount',array("error"=>"authcode was not updated :: " . $args['authcode']));
					}
					else
					{
						// Log authcode was updated
						$args['userLogger']->info("AUTHCODE: " . $args['authcode'] ." has been flagged as used");
					}

					$statusMsg = statusmsg::getStatusMsg("created",(new \ReflectionClass(self::class))->getShortName());

					return utilities::replaceStatusMsg( $statusMsg, "::USERNAME::",$args['username']);
				}
			}

			return $processAccountRequest;
		}

		return $validate;
	}

	public function updateAccount()
	{

	}

	/**
	 *  Private Methods
	 */

    /**
     * @param array $args
     * @return array|string
     * @throws \ReflectionException
     */
	private static function processNewAccountData(array $args)
	{
		// Check to see if we need to validate account count against Email
		if(settings::CHECKEMAIL === true)
		{
			if($args['email'] != errormsg::getErrorMsg("noaccountemail", (new \ReflectionClass(self::class))->getShortName()) and $args['email'] >= settings::NUMBER_OF_ACCOUNTS_ALLOWED)
			{
				return errormsg::getErrorMsg("tomanyaccounts", (new \ReflectionClass(self::class))->getShortName());
			}
		}

		// Check to see if we need to validate account count against Ip
		if(settings::CHECKIP === true)
		{
			if($args['ip'] != errormsg::getErrorMsg("noaccountip", (new \ReflectionClass(self::class))->getShortName()) and $args['ip'] >= settings::NUMBER_OF_ACCOUNTS_ALLOWED)
			{
				return errormsg::getErrorMsg("tomanyaccounts", (new \ReflectionClass(self::class))->getShortName());
			}
		}

		// Make sure we don't have an existing username
		if($args['helper'] === true)
		{
			return errormsg::getErrorMsg("accountexits", (new \ReflectionClass(self::class))->getShortName());
		}

		// Make sure the authcode is valid
		if($args['authcode'] === errormsg::getErrorMsg("authnotfound", "authcodeModel"))
		{
			return ['msg'=>errormsg::getErrorMsg("authnotfound", "authcodeModel")];
		}

		return statusmsg::getStatusMsg("checkspassed", (new \ReflectionClass(self::class))->getShortName());
	}

    /**
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	private static function getAccountEmail(array $args)
	{
		try {
			$results = $args['db']::table(self::$accountsTable)
					->select('account_id')
					->where('email', '=', $args['email'])
					->count();

			if ($results === 0)
			{
				return errormsg::getErrorMsg("noaccountemail", (new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		} catch (Error $ex) {
			$args['errorLogger']->error('accountModel::getAccountEmail',array("error"=>new \ReflectionClass(self::class))->getShortName() . " " . $ex->getMessage());
			throw new \Error (errormsg::getErrorMsg("getaccountemail", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	private static function getAccountUsername(array $args)
	{
		try {
			$results = $args['db']::table(self::$accountsTable)
					->select('account_id')
					->where('username', '=', $args['username'])
					->exists();

			if ($results === false)
			{
				return errormsg::getErrorMsg("noaccountusername", (new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		}
		catch (Error $ex) {
			$args['errorLogger']->error('accountModel::getAccountUsername',array("error"=>new \ReflectionClass(self::class))->getShortName() . " " . $ex->getMessage());
			throw new \Error (errormsg::getErrorMsg("getaccountusername", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}

    /**
     * @param array $args
     * @return string
     * @throws \ReflectionException
     */
	private static function getAccountIp(array $args)
	{
		try {
			$results = $args['db']::table(self::$accountsTable)
					->select('account_id')
					->where('create_ip', '=', $args['ip'])
					->count();

			if ($results === 0)
			{
				return errormsg::getErrorMsg("noaccountip", (new \ReflectionClass(self::class))->getShortName());
			}

			return $results;

		}
		catch (Error $ex) {
			$args['errorLogger']->error('accountModel::getAccountIp',array("error"=>new \ReflectionClass(self::class))->getShortName() . " " . $ex->getMessage());
			throw new \Error (errormsg::getErrorMsg("getaccountip", (new \ReflectionClass(self::class))->getShortName()) . " " . $ex->getMessage());
		}
	}
}

?>
