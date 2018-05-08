<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\controllers
 * CLASS: accountController
 ******************************************************************/

namespace swgAS\swgAPI\controllers;

// Use
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\controllers\baseController;
use swgAS\helpers\utilities;
use swgAS\swgAPI\models\accountModel;

/**
 * Class accountController
 * @package swgAS\swgAPI\controllers
 */
class accountController extends baseController
{

    /**
	 * @method getAccount
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \ReflectionException
     */
	public function getAccount(ServerRequestInterface $request, ResponseInterface $response)
	{
		$account = accountModel::getAccounts(array(
			"db"=>$this->getCIElement('db'),
          	"errorlogger"=>$this->getCIElement('swgErrorLog'),
          	"apiLogger"=>$this->getCIElement('swgAPILog'),
          	"userLogger"=>$this->getCIElement('swgUsersLog'))
        );

		return $response->withJson($account,200);
	}

    /**
	 * @method  checkAccount
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \ReflectionException
     */
	public function checkAccount(ServerRequestInterface $request, ResponseInterface $response)
    {
		$accounts = accountModel::getAccount(array (
				"username"=>$request->getParam('username'),
				"email"=>$request->getParam('email'),
				"ip"=>utilities::unobscureData($request->getParam('uip')),
				"db"=>$this->getCIElement('db'),
				"errorLogger"=>$this->getCIElement('swgErrorLog'),
				"apiLogger"=>$this->getCIElement('swgAPILog'),
				"userLogger"=>$this->getCIElement('swgUsersLog')
		));

		return $response->withJson($accounts,200);
	}

    /**
	 * @method  addAccount
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \ReflectionException
     */
	public function addAccount(ServerRequestInterface $request, ResponseInterface $response)
	{
		$newaccount = accountModel::addAccount(array(
				"username"=>$request->getParam('username'),
				"email"=>$request->getParam('email'),
				"password"=>$request->getParam('password'),
				"repassword"=>$request->getParam('repassword'),
				"authcode"=>$request->getParam('authcode'),
				"ip"=>utilities::unobscureData($request->getParam('uip')),
				"db"=>$this->getCIElement('db'),
				"errorLogger"=>$this->getCIElement('swgErrorLog'),
				"apiLogger"=>$this->getCIElement('swgAPILog'),
				"userLogger"=>$this->getCIElement('swgUsersLog'),
				"attemptsLogger"=>$this->getCIElement('swgMultipleAttemptsLog')
		));

		return $response->withJson($newaccount,201);
	}
}
