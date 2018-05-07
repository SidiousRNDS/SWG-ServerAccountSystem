<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 *
 *****************************************************************/
    require '../vendor/autoload.php';


    // Use
    use Tuupola\Middleware\JwtAuthentication;

    // swgAS Use
    use swgAS\config\settings;

    require '../app/config/di.php';

    session_start();

    $swgAS = new \Slim\App($ci);

    $swgAS->add(new JwtAuthentication([
            "path" => "/api",
            "ignore" => ["/api/token"],
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

    require '../app/routes/routes.php';

    $swgAS->run();
?>
