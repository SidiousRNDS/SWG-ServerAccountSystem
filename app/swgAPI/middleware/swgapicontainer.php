<?php

// Use
use \Slim\Container as Container;
use \Tuupola\Middleware\CorsMiddleware;
use \Monolog\Logger;
use \config\settings;

// swgAPI Use

$ci = new Container();


// DB
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
	"driver"		=> "mysql",
	"host"			=> settings::DBHOST,
	"database"		=> settings::DBNAME,
	"username"		=> settings::DBUSER,
	"password"		=> settings::DBPASS,
	"charset"		=> "utf8",
	"collation"		=> "utf8_general_ci",
	"prefix"		=> ""
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$ci['db'] = function($ci) use ($capsule) {
	return $capsule;
};

// Logs
$ci['swgAPILog'] = function($ci) {
	$apiLogger = new Logger('apiLogger');
	$file_handler = new \Monolog\Handler\StreamHandler(settings::LOGPATH.settings::APILOG);
	$apiLogger->pushHandler($file_handler);
	return $apiLogger;
};

$ci['swgUsersLog'] = function($ci) {
	$userLogger = new Logger('userLogger');
	$file_handler = new \Monolog\Handler\StreamHandler(settings::LOGPATH.settings::USERSLOG);
	$userLogger->pushHandler($file_handler);
	return $userLogger;
};

$ci['swgErrorLog'] = function($ci) {
	$errorLogger = new Logger('errorLogger');
	$file_handler = new \Monolog\Handler\StreamHandler(settings::LOGPATH.settings::ERRORLOG);
	$errorLogger->pushHandler($file_handler);
	return $errorLogger;
};

$ci['swgPassResetLog'] = function($ci) {
	$passResetLogger = new Logger('passresetLogger');
	$file_handler = new \Monolog\Handler\StreamHandler(settings::LOGPATH.settings::PASS_RESET_LOG);
	$passResetLogger->pushHandler($file_handler);
	return $passResetLogger;
};

$ci['swgMultipleAttemptsLog'] = function($ci) {
	$attemptsLogger = new Logger('attemptsLogger');
	$file_handler = new \Monolog\Handler\StreamHandler(settings::LOGPATH.settings::MULTIPLE_ATTEMPTS_LOG);
	$attemptsLogger->pushHandler($file_handler);
	return $attemptsLogger;
};

// JWT
$ci["JwToken"] = function($ci) {
	return new stdClass;
};


// Cors
$ci["CorsMiddleware"] = function ($ci) {
    return new CorsMiddleware([
        "origin" => ["*"],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
        "headers.expose" => ["Authorization", "Etag"],
        "credentials" => true,
        "cache" => 60,
    ]);
};

// Controllers
$ci[\swgAPI\controllers\accountController::class] = function(Container $ci) {
	return new \swgAPI\controllers\accountController($ci);
};

$ci[\swgAPI\controllers\authcodeController::class] = function(Container $ci) {
	return new \swgAPI\controllers\authcodeController($ci);
};

$ci[\swgAPI\controllers\characterController::class] = function(Container $ci) {
	return new \swgAPI\controllers\characterController($ci);
};

$ci[\swgAPI\controllers\galaxybanController::class] = function(Container $ci) {
	return new \swgAPI\controllers\galaxybanController($ci);
};

$ci[\swgAPI\controllers\userbanController::class] = function(Container $ci) {
	return new \swgAPI\controllers\userbanController($ci);
};

$ci[\swgAPI\controllers\statusController::class] = function(Container $ci) {
	return new \swgAPI\controllers\statusController($ci);
};

$ci[\swgAPI\controllers\tokenController::class] = function(Container $ci) {
	return new \swgAPI\controllers\tokenController($ci);
};

?>
