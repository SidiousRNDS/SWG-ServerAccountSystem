<?php

namespace swgAPI\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\controllers
 * CLASS: accountController
 ******************************************************************/

// Use
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

// swgAPI Use
use \swgAPI\models\accountModel;

class accountController extends baseController
{

	/**
	 * Summary of getAccount
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return mixed
	 */
	public function getAccount(ServerRequestInterface $request, ResponseInterface $response, array $args)
	{
		$account = accountModel::getAccounts(array("db"=>$this->getCIElement('db'),"errorlogger"=>$this->getCIElement('swgErrorLog'),"apiLogger"=>$this->getCIElement('swgAPILog'),"userLogger"=>$this->getCIElement('swgUsersLog')));

		return $response->withJson($account,200);
	}

	/**
	 * Summary of checkAccount
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return mixed
	 */
	public function checkAccount(ServerRequestInterface $request, ResponseInterface $response, array $args)
	{
		$accounts = accountModel::getAccount(array (
				"username"=>$request->getParam('username'),
				"email"=>$request->getParam('email'),
				"ip"=>$request->getParam('ip'),
				"db"=>$this->getCIElement('db'),
				"errorLogger"=>$this->getCIElement('swgErrorLog'),
				"apiLogger"=>$this->getCIElement('swgAPILog'),
				"userLogger"=>$this->getCIElement('swgUsersLog')
		));

		return $response->withJson($accounts,200);
	}

	public function addAccount(ServerRequestInterface $request, ResponseInterface $response, array $args)
	{
		$newaccount = accountModel::addAccount(array(
				"username"=>$request->getParam('username'),
				"email"=>$request->getParam('email'),
				"password"=>$request->getParam('password'),
				"repassword"=>$request->getParam('repassword'),
				"authcode"=>$request->getParam('authcode'),
				"ip"=>$request->getParam('ip'),
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