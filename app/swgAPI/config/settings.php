<?php

namespace swgAPI\config;

/**
 * Summary of config
 */
class settings
{
	/**
	 * Database vars
	 * @var string DBUSER
	 * @var string DBPASS
	 * @var string DBNAME
	 * @var string DBHOST
	 */
	const DBUSER = "swgemu";
	const DBPASS = "123456";
	const DBNAME = "swgemu";
	const DBHOST = "localhost";

	/**
	*	Logging vars
	*	@var string LOGPATH - Path to the log files
	*	@var string APILOG - API Log Filename
	*	@var string USERSLOG - User Log Filename
	*	@var string ERRORLOG - Error Log Filename
	*	@var string PASSRESETLOG - Password Reset Log Filename
	*	@var string MULTIPLEATTEMPTSLOG - Multiple attempts to create an account log
	*/
	const LOGPATH = "../../storage/logs/";
	const APILOG = "swgAPILog";
	const USERSLOG = "swgUsersLog";
	const ERRORLOG = "swgErrorLog";
	const PASS_RESET_LOG = "swgPassResetLog";
	const MULTIPLE_ATTEMPTS_LOG = "swgMultipleAttemptsLog";

	/**
	 * Account settings
	 * @var int NUMBEROFACCOUNTSALLOWED - Set the total number of accounts a user can have on the server with out a special authcode
	 * @var bool CHECKEMAIL	- Should we validate account count against email address
	 * @var bool CHECKIP - Should we validate account count against ip
	 * @var string PWSECRET - Password seceret for the password
	 * @var string CRYPTHASH - Crytpo that the EMU project uses (if you change this you will need to change the C++ code on your game server)
	 * @var int OPENSSLBYTES_LENGTH - Length of the string that will be generated
	 * @var int STATIONID_START - Starting number for your station IDs - You should set this above what your last station ID in  your accounts table is current the value should be good
	 * @var int STATIONID_END - Ending number for your station IDs - This gives you a large range for station id's so you should not have to change this but double check your accounts table to be sure
	 */
	const NUMBER_OF_ACCOUNTS_ALLOWED = 2;
	const CHECKEMAIL = true;
	const CHECKIP = true;
	const PWSECRET = "swgemus3rc37!";
	const CRYPTHASH = "sha256";
	const OPENSSLBYTES_LENGTH = 20;
	const STATIONID_START = 4000000000;
	const STATIONID_END = 6999999999;

	/**
	 * Auth Code Settings
	 * @var string MAIN_CODE_PREFIX - This is the primary code that will force the checks to see if the user has more then 2 acounts
	 * @var string EXTENDED_CODE_PREFIX - This is the code that will bypass all checks for users that are allowed to have multiple household accounts (family accounts)
	 * @var int CODE_LENGTH_PRIMARY - Number of characters that the primary code should be
	 * @var int CODE_LENGTH_SECONDARY - Number of characters that the secondary code should be
	 * @var bool USE_SECONDARY - Tells use if we should use the secondary length or not
	 * @var string CODE_CHARS - The characters that will make up the primary and secondary sections of the auth code (0,I,i,O,o have been removed from the defaults)
	 * @var bool DIVIDERS - If set to true it will add three - to the authcode
	 */
	const MAIN_CODE_PREFIX = "RNDS1";
	const EXTENDED_CODE_PREFIX = "RNDS2";
	const CODE_LENGTH_PRIMARY = 7;
	const CODE_LENGTH_SECONDARY = 7;
	const USE_SECONDARY = true;
	const CODE_CHARS = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
	const DIVIDERS = true;



	/**
	* Jwt Auth
	* @var bool JWTSECURR
	* @var string JWTSECRET
	* @var string APIUSER
	*/
	const JWTSECURE = false;
	const JWTSECRET = "th1s1sju$7@Pl@c3H0ld3r4y0ur$3cr3t";
	const APIUSER = "swgApi";

}

?>