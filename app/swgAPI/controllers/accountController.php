<?php

namespace swgAS\swgAPI\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\controllers
 * CLASS: accountController
 ******************************************************************/

// Use
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\swgAPI\utils\utilities;
use swgAS\swgAPI\models\accountModel;

class accountController extends baseController
{

	/**
	 * Summary of getAccount
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return mixed
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
	 * Summary of checkAccount
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return mixed
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

?>