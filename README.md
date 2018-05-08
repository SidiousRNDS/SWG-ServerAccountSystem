# SWG Server and Account System

The system allows for a server to setup a separate client sign up system from the standard SWGEmu system, an authocode is required for users to create a
new game account on the server. The authcode is generated by the server admin and sent to the user via email/ticket system at which time the user will go
to the provided URL and enter in their username, password, email, and authcode and then the account will be created as long as all the requirements are met.

The system will do validation on the username (if a user with the same name exists it will not create the account), email address (it will look to see if the user has used this email address previously and if they have more the the allowed times it will not create the account), ip (it will look to see if the users
ip has been used previously and if they have more the the allowed times it will not create the account).

All these settings are editable in the settings.php file you can turn on validation for IP and Email as well as increase the number of accounts that a user can have.

The only requirements for the front end if you choose not to use the default front end is that you can get and pass a bearer token back to the API with out being able to do that it will always fail. (see default front end for examples).

The admin section of the site also allows you to do your game patches as well it will store the patch notes, TRE file, and rewrite your live.cfg with the updated data for easy use to create patchs for your server.

As of 8 March 2018 00:36 this is not a fully complete program  there are still some things I am working on for this system but all the basics are there as described above.
Still to come items include:
* Allowing users to change their passwords
* Administrators being able to ban users and/or accounts with out actually having to login to the game
* Launcher Support for the RNDS Launcher (that will be being released as open source as soon as v.1.0.0 of SWGSAS has been completed)
* More charts and graphs to monitor users on your server currently there are only three
    * Total Users Online over the last 7 days
    * Total Users Online over the last 24 hours
    * Total Unique IP's online for the last Month

If you would like to see what the layout looks like for either the client or Admin sections you can use the links below to check them out
### Example
* [SWG Server System - Client](http://swgusers.rnds.io)
* [SWG Server System - Admin](http://swgusers.rnds.io/admin)

In the Admin section there are three different roles you can login as
* Admin
    * username: testAdmin    
    * password: 123456
    
* CSR
    * username: testCRS
    * password: 123456
   
* DEV
    * username: testDEV
    * password: 123456
    
All have slightly different permissions and the only role that is not currently available to login into is Owner which I will make available once this is officially launched.

### Install
Clone the repository
``` bash
$ git clone https://github.com/SidiousRNDS/SWG-ServerAccountSystem.git
```
Run composer install
``` bash
$ composer install
```
Make sure to set the permissions for storage/logs to your webserver's user (www-data) and make sure they are chmod to 755 as well.
``` bash
$ sudo chwon -R www-data:www-data storage/
$ sudo chmod 755 storage/
$ sudo chmod 755 storage/logs
```

Install MongoDb
``` bash
$sudo apt-get install mongodb
$sudo apt-get install php-pear
$sudo pecl install mongodb
```

If you are running apache
``` bash
$sudo vi /etc/php/7.1/apache2/php.ini
```
Add extension=mongodb.so in the extension section of the ini file and restart apache.

``` bash
$sudo serivce apache2 restart
```
### app/configs/settings_dist.php
After you have got everything setup on your server you are going to want to update this file with all your information.
The following items must be setup for this application to work.

* BASE_URL - this should hold your sites URL
* SERVER_TIME_ZONE - This should be set to your timezone
* G_CAPTCHA_KEY - If you don't have one of these you can go to (https://www.google.com/recaptcha/intro/android.html) and create one.

Game Server Settings
* SERVER_NAME - The name of your game server
* LIVE_GAME_SERVER - This is either the IP or URI to your live game server
* TEST_GAME_SERVER - This is either the IP or URI to your test game server
* STATUS_PORT - If you have changed this from the default port you will want to make sure you change it here as well.
* UPDATE_LIVE_PATH - This is where you will store your TRE files and config files for download by your launcher for live.
* UPDATE_TEST_PATH - This is wehre you will store your TRE files config files for download by your launcher for test.
* LIVE_CONFIG_FILE - This is the name of the live.cfg file you will use for the live game server.
* TEST_CONFIG_FILE - This is the name of the live.cfg file you will use for the test game server.

DB Settings
* DBUSER - The user that connects to your game database.
* DBPASS - The password for the user that connects to your game database.
* DBNAME - The name of the game database.
* DBHOST - The host location of the game database.

Game Account Settings
* NUMBER_OF_ACCOUNTS_ALLOWED - This is the number of accounts you will allow a single user to have on your server (default is 2)
* CHECKEMAIL - This sets if the authcode system should check to see how many times the users email has been used to create accounts.
* CHECKIP - This sets if the authcode system should check to see how many times the users IP address has been used to create accounts.
* PWSECRET - This should have been changed in your SWGEMU config file if it has not well you can just leave it but if you did then please change it to what you have in your SWGEMU config file
* CRYPTHASH - Unless you changes how the SWGEMU creates password don't touch this

Authcode Settings
* USE_AUTHCODES - Set this to true if you want to use Authcodes false if you do not (default is true)
* PRIMARY_CODE_PREFIX - Set this to what every you like or leave it as it is this is completely up to you.
* EXTENDED_CODE_PREFIX - Set this to what every you like or leave it as it is this is completely up to you.
* CODE_LENGTH_PRIMARY - This is the number of characters that will be in the first section of the authcode (set it to what ever you like default is 7 chars)
* CODE_LENGTH_SECONDARY  - This is the number of characters that will be in the second section of the authcode (set it to what ever you like default is 7 chars
* USE_SECONDARY - If you want a short code then set this to false and it will not generate a secondary part of the authcode.
* DIVEIDERS - Set this to false if you want to turn off the - between the code sections 

JWT Auth Settings
* JWTSECURE - If you are not running this over SSL then keep this to false
* JWTSECRET - Change this before you go live for security
* APIUSER - you can set this to what ever you would or just leave it.

Anything else in this file that is not listed above should be left alone at this time.

### Built with
* [PHP 7.1](http://php.net) - PHP ^7.1 is required
* [MongoDB](https://www.mongodb.com/) - MongoDB
* [Bootstrap](https://getbootstrap.com/) - v3.7
* [Less](http://lesscss.org/) - Less CSS
* [Slim](https://www.slimframework.com/) - Slim framework
* [Slim JWT Auth](https://github.com/tuupola/slim-jwt-auth) - JWT Auth for Slim framework
* [Slim Flash](https://github.com/slimphp/Slim-Flash) - Flash Messages for the Slim Framework
* [Mono Log](https://github.com/Seldaek/monolog) - Mono Log logger
* [Twig](https://twig.symfony.com) - Twig Templates
* [Twig-View](https://github.com/slimphp/Twig-View) - Slim Twig View
* [Google Charts](https://developers.google.com/chart/) - Google Charts
* [DataTables](https://datatables.net) - Advanced interaction controls for HTML Tables
* [Summernote](https://summernote.org/) - Summer note WYISWIG for Text Areas
* [WriteiniFile](https://github.com/Magicalex/WriteiniFile) - Write-ini-file php library for create, remove, erase, add, and update ini file.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/SidiousRNDS/SWG-ServerAccountSystem/tags).

## Authors

* **Ian Ford** aka(Sidious)- *Initial work* - [SidiousRNDS](https://github.com/SidiousRNDS)

See also the list of [contributors](https://github.com/SidiousRNDS/SWG-ServerAccountSystem/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
