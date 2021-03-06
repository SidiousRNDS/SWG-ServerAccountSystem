<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\controllers
 * CLASS: authcodeController
 ******************************************************************/

namespace swgAS\swgAPI\controllers;

// Use
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\controllers\baseController;
use swgAS\swgAPI\models\authcodeModel;
use swgAS\config\settings;

/**
 * Class authcodeController
 * @package swgAS\swgAPI\controllers
 */
class authcodeController extends baseController
{
    
    /**
     * @method adminGenerateAuthcode
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return mixed
     * @throws \ReflectionException
     */
	public function adminGenerateAuthCode(ServerRequestInterface $request, ResponseInterface $response)
	{
			authcodeModel::createAuthCode(array(
				"db"=>$this->getCIElement('db'),
				"errorlogger"=>$this->getCIElement('swgErrorLog'),
				"flash"=>$this->getCIElement('flash'),
            	'username'=>$request->getParam('username'),
            	'email' => $request->getParam('email'),
            	'prefix' => $request->getParam('prefix'))
			);

            $uri = $request->getURI()->withPath($this->getCIElement('router')->pathFor('createauth'));

            return $response->withRedirect($uri);
	}
}
