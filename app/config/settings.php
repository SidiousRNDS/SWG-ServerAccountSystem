<?php

namespace swgAS\config;

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
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

     const BASE_URL = "http://swgusers.rnds.io";
     const TOKEN_URL = "/api/token";
     const STATUS_URL = "/api/v1/status";

     const SERVER_TIME_ZONE = "America/New_York";
  /**
  * Template location
  * @var string TEMPLATES - This is the active templates directory you want to use for both admin and client
  * if you want to add your own templates you can just add them to the views directory in the client and admin section
  * and change the templates var below to reflect the new folder location. (the directory must be named the same for both locations)
  */
  const TEMPLATES = "default";

  /**
  *  Google Captcha Key
	* @var string G_CAPTCHA_KEY - Google Captcha Key if you are using it
  */
  const G_CAPTCHA_KEY = "6Le4iSQTAAAAAJg5IbvWjQOVbru4LDZPI3cB5CO2";

  /**
   * ipInfoDB
   * @var string IP_INFO_DB_KEY - You must set up your own account to use this functionality - NOT USED AT THIS TIME
   */
  //const IP_INFO_DB_KEY = "683864c8e14039b85e0235ffb7ce2c6fc26f5fd1d74f82f48e84e1bef2cf9d78";

  /**
   * Game Server Info
   * @var string SERVER_NAME - Name of the server you want to display
   * @var string LIVE_GAME_SERVER - the URI or IP of your live game server
   * @var string TEST_GAME_SERVER - the URI or IP of you test game server (leave empty if you don't have one)
   * @var int STATUS_PORT - The Game Server Status Port
   */
  const SERVER_NAME = "SWG Rehab";
  const LIVE_GAME_SERVER = "live.swgrehab.com";
  const TEST_GAME_SERVER = "test.swgrehab.com";
  const STATUS_PORT = 44455;

  /**
   * Update locations and files
   * @var string UPDATE_PATH - Path to where all the files live
   * @var string LIVE_CONFIG_PATH - Path to where the live.cfg file lives
   * @var string LOGIN_CONFIG_PATH - Path to where the login.cfg file lives
   * @var string TEXTURE_CONFIG_PATH - Path to were the texture.cfg file lives
   * 
   * If you are not using the RO launcher you may not have these and they can remain empty
   * @var string TRE_FOLDER - Folder to where the TRE files live
   * @var DYN_LAUNCHER_IMAGE_FOLDER - Folder to where the Dyn Launcher Image lives
   * @var TEXTURE_FOLDER - Folder to where the texture files live
   * @var LAUNCHER_UPDATE_XML - File name for the launcher gameupdates
   */
  const UPDATE_LIVE_PATH = "/var/www/rehab-patch/live";
  const UPDATE_TEST_PATH = "/var/www/rehab-patch/test";
  const LIVE_CONFIG_FILE = "Rehab_live.cfg";
  const TEST_CONFIG_FILE = "Rehab_test.cfg";
  const LOGIN_CONFIG_FILE = "Rehab_login.cfg";
  const TEXTURE_CONFIG_FILE = "Rehab_texture.cfg";
  const LAUNCHER_UPDATE_XML = "launcher_update.xml";
  const TRE_FOLDER = "tre/";
  const DYN_LAUNCHER_IMAGE_FOLDER = "dyn_launcher_image/";
  const TEXTURE_FOLDER = "texture/";


  /**
   * Database vars
   * @var string DBUSER
   * @var string DBPASS
   * @var string DBNAME
   * @var string DBHOST
   * @var string MONGO_STATUS - This is the status database that holds the status database
   * @var string MONGO_ADMIN - This is the database that holds all data
   * @var string ADMIN_PASSWORD_SALT - This is the salt that encrypts password for the admin section (you can change it to what ever you want)
   */
   const DBUSER = "swgemu";
   const DBPASS = "123456";
   const DBNAME = "swgemu";
   const DBHOST = "localhost";

   const MONGO_STATUS = "swgStatus";
   const MONGO_ADMIN = "swgASAdmin";
   const ADMIN_PASSWORD_SALT = "eYXdQT7B32xWHSNvbeGx";

   /**
	* Logging vars
	* @var string LOGPATH - Path to the log files
 	* @var string APILOG - API Log Filename
 	* @var string USERSLOG - User Log Filename
 	* @var string ERRORLOG - Error Log Filename
 	* @var string PASSRESETLOG - Password Reset Log Filename
 	* @var string MULTIPLEATTEMPTSLOG - Multiple attempts to create an account log
    * @var string ADMINLOG - Admin log logs actions for the admin section
 	*/
 	const LOGPATH = "../storage/logs/";
 	const APILOG = "swgAPILog";
 	const USERSLOG = "swgUsersLog";
 	const ERRORLOG = "swgErrorLog";
 	const PASS_RESET_LOG = "swgPassResetLog";
 	const MULTIPLE_ATTEMPTS_LOG = "swgMultipleAttemptsLog";
    const ADMINLOG = "adminLog";
    const ADMINLOCKLOG = "adminLockLog";

 	/**
 	* Account settings
 	* @var int NUMBEROFACCOUNTSALLOWED - Set the total number of accounts a helper can have on the server with out a special authcode
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
	 * @var bool USE_AUTHCODES - If you don't want to use authcodes but want to use the rest of the system set this to false default is true
 	 * @var string MAIN_CODE_PREFIX - This is the primary code that will force the checks to see if the helper has more then 2 acounts
 	 * @var string EXTENDED_CODE_PREFIX - This is the code that will bypass all checks for users that are allowed to have multiple household accounts (family accounts)
 	 * @var int CODE_LENGTH_PRIMARY - Number of characters that the primary code should be
 	 * @var int CODE_LENGTH_SECONDARY - Number of characters that the secondary code should be
 	 * @var bool USE_SECONDARY - Tells use if we should use the secondary length or not
 	 * @var string CODE_CHARS - The characters that will make up the primary and secondary sections of the auth code (0,I,i,O,o have been removed from the defaults)
 	 * @var bool DIVIDERS - If set to true it will add three - to the authcode
 	 * @var bool AUTHCODE_GENERATE - Should we allow the generation of authcodes
 	 * @var string AUTHCODE_GENERATE_USER - Name that is allowed to generate authcodes (this is passed in the post request)
 	 * @var string AUTHCODE_GENERATE_SALE - Salt to encode the helper
 	 */
 	const USE_AUTHCODES = true;
 	const PRIMARY_CODE_PREFIX = "RNDS1";
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

 	const ADMIN_SECTIONS = [
 		'authcodes',
		'gameupdates',
		'players',
		'bans',
		'reports',
		'users',
		'roles',
		'configurations'
	];
 }

 ?>
