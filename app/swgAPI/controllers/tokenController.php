<?php
namespace swgAS\swgAPI\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\controllers
 * CLASS: tokenController
 ******************************************************************/

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

// Use SWGAS
use swgAS\swgAPI\models\tokenModel;

class tokenController extends baseController
{
	/**
	 * Summary of getToken
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return mixed
	 */
	public function getToken(ServerRequestInterface $request, ResponseInterface $response, array $args)
	{
		$token = tokenModel::genToken($request, $response, array("token"=>$this->getCIElement('JwToken'),"apiLogger"=>$this->getCIElement('swgAPILog'),"errorLogger"=>$this->getCIElement('swgErrorLog')));

		return $response->withJson($token, 201);
	}
}

?>
