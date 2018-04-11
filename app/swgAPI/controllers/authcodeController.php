<?php

namespace swgAS\swgAPI\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\controllers
 * CLASS: authcodeController
 ******************************************************************/

// Use
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\controllers\baseController;
use swgAS\swgAPI\models\authcodeModel;
use swgAS\config\settings;

class authcodeController extends baseController
{

	public function adminGenerateAuthCode(ServerRequestInterface $request, ResponseInterface $response)
	{
			$authCode = authcodeModel::createAuthCode(array(
				"db"=>$this->getCIElement('db'),
				"errorlogger"=>$this->getCIElement('swgErrorLog'),
				"flash"=>$this->getCIElement('flash'),
            	'username'=>$request->getParam('username'),
            	'email' => $request->getParam('email'),
            	'prefix' => $request->getParam('prefix'))
			);

			return $response->withRedirect('/admin/dashboard/authcodes/createauth');
	}
}

?>
