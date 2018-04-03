<?php

namespace config;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: config
 * CLASS: settings
 ******************************************************************/

/**
 * Summary of config
 */
 class settings
 {
 	/**
 	* ipInfoDB
 	* @var string IP_INFO_DB_KEY - You must set up your own account to use this functionality
 	*/
 	const IP_INFO_DB_KEY = "683864c8e14039b85e0235ffb7ce2c6fc26f5fd1d74f82f48e84e1bef2cf9d78";

 	/**
 	 * Database vars
 	 * @var string DBUSER
 	 * @var string DBPASS
 	 * @var string DBNAME
 	 * @var string DBHOST
 	 * @var string MONGO_STATUS - This is the status database that holds the status database
 	 * @var string MONGO_ADMIN - This is the database that holds all the admin data (TODO make a better description)
 	 */
 	const DBUSER = "swgemu";
 	const DBPASS = "123456";
 	const DBNAME = "swgemu";
 	const DBHOST = "localhost";

 	const MONGO_STATUS = "swgStatus";
 	const MONGO_ADMIN = "swgAdmin";

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
 	 * @var bool AUTHCODE_GENERATE - Should we allow the generation of authcodes
 	 * @var string AUTHCODE_GENERATE_USER - Name that is allowed to generate authcodes (this is passed in the post request)
 	 * @var string AUTHCODE_GENERATE_SALE - Salt to encode the user
 	 */
 	const MAIN_CODE_PREFIX = "RNDS1";
 	const EXTENDED_CODE_PREFIX = "RNDS2";
 	const CODE_LENGTH_PRIMARY = 7;
 	const CODE_LENGTH_SECONDARY = 7;
 	const USE_SECONDARY = true;
 	const CODE_CHARS = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
 	const DIVIDERS = true;
 	const AUTHCODE_GENERATE = true;
 	const AUTHCODE_GENERATE_USER = "DarthVaderIsTheTrueSithLord";
 	const AUTHCODE_GENERATE_SALT = "$17hRul37h3Galaxy";

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