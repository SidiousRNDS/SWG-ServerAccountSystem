<?php

// Use
use Tuupola\Middleware\JwtAuthentication;

// swgAPI Use
use config\settings;


require '../../vendor/autoload.php';

require '../../app/swgAPI/middleware/swgapicontainer.php';

$swgapi = new \Slim\App($ci);


$swgapi->add(new JwtAuthentication([
		"path" => "/",
		"ignore" => ["/v1/token"],
		"secret" => settings::JWTSECRET,
		"secure" => settings::JWTSECURE,
		"error" => function ($response, $arguments) {
			$data["status"] = "error";
			$data["message"] = $arguments["message"];
			return $response
				->withHeader("Content-Type", "application/json")
				->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
		},
		"before" => function($request, $arguments) use($ci) {
			$ci["JwToken"] = $arguments["decoded"];
		}
]));

require '../../app/swgAPI/routes/routes.php';

$swgapi->run();

?>
