<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\models
 * CLASS: tokenModel
 ******************************************************************/

namespace swgAS\swgAPI\models;

// Use
use \Illuminate\Database\Eloquent\Model as Model;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Tuupola\Base62;
use \Firebase\JWT\JWT;

// swgAS Use
use swgAS\config\settings;
use swgAS\helpers\utilities as Utils;

/**
 * Class tokenModel
 * @package swgAS\swgAPI\models
 */
class tokenModel extends Model
{
    /**
	 * @method genToken
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
	public static function genToken(ServerRequestInterface $request, ResponseInterface $response, array $args)
	{
		$now = new \DateTime();
		$future = new \DateTime("now +2 hours");

		$jti = (new Base62)->encode(random_bytes(16));

		$payload = [
			"iat" => $now->getTimeStamp(),
			"exp" => $future->getTimeStamp(),
			"jti" => $jti,
			"sub" => settings::APIUSER
		];

		$secret = settings::JWTSECRET;
		$token = JWT::encode($payload, $secret, "HS256");

		$data["token"] = $token;
		$data["status"] = "ok";
		$data["expires"] = $future->getTimestamp();

		$client_ip = Utils::getClientIp();

		$args['apiLogger']->info('TOKEN:',array("clientIP"=>$client_ip,"Token"=>$token, "Expires"=>$future->getTimestamp()));

		return $data;
	}
}