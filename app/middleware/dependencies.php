<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************/

// Use
use \Slim\Container as Container;
use \Tuupola\Middleware\CorsMiddleware;
use \Monolog\Logger;

// swgAS Use
use swgAS\config\settings;


$ci = new Container();

// DB
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
    "driver"		=> "mysql",
    "host"			=> settings::DBHOST,
    "database"	=> settings::DBNAME,
    "username"	=> settings::DBUSER,
    "password"	=> settings::DBPASS,
    "charset"		=> "utf8",
    "collation"	=> "utf8_general_ci",
    "prefix"		=> ""
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$ci['db'] = function($ci) use ($capsule) {
    return $capsule;
};


// User IP
$ci['userIP'] = function($ci) {
    return \swgAS\swgAPI\utils\utilities::getClientIp();
};

// Views
// Client Views
$ci['views'] = function($ci) {
    $clientViews = new \Slim\Views\Twig('../app/views/'.settings::TEMPLATES, ['cache' => false]);

    $basePath = rtrim(str_ireplace('index.php', '', $ci['request']->getUri()->getBasePath()), '/');
    $clientViews->addExtension(new \Slim\Views\TwigExtension($ci['router'], $basePath));

    return $clientViews;
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
$ci[\swgAS\swgAPI\controllers\accountController::class] = function(Container $ci) {
    return new \swgAS\swgAPI\controllers\accountController($ci);
};

$ci[\swgAS\swgAPI\controllers\authcodeController::class] = function(Container $ci) {
    return new \swgAS\swgAPI\controllers\authcodeController($ci);
};

$ci[\swgAS\swgAPI\controllers\tokenController::class] = function(Container $ci) {
    return new \swgAS\swgAPI\controllers\tokenController($ci);
};

?>
