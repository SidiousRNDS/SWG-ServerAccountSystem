<?php

namespace swgAS\swgAdmin\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\controllers
 * CLASS: adminloginController
 ******************************************************************/
 // Use
 use \Psr\Http\Message\ServerRequestInterface;
 use \Psr\Http\Message\ResponseInterface;

 // Use swgAS
 use swgAS\controllers\baseController;
 use swgAS\swgAdmin\models\adminloginModel;

class adminloginController extends baseController
{
  public function login(ServerRequestInterface $request, ResponseInterface $response)
  {
      $adminLogin = new adminloginModel();

    $login = $adminLogin->authLogin(array(
                "mongodb"=>$this->getCIElement('mongodb'),
                "errorlogger"=>$this->getCIElement('swgErrorLog'),
                "adminlogger"=>$this->getCIElement('adminLog'),
                "username"=>$request->getParam('username'),
                "password"=>$request->getParam('password'))
    );

    if ($login != true)
    {
      return $response->withJson($login,401);
    }

    // Redirect to Admin Dashboard
      return $response->withJson("Authorized", 200);
  }
}

?>
