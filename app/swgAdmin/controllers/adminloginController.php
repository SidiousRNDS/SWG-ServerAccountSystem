<?php

namespace swgAS\swgAdmin\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAdmin\controllers
 * CLASS: adminloginController
 ******************************************************************/
 // Use
 use \Psr\Http\Message\ServerRequestInterface;
 use \Psr\Http\Message\ResponseInterface;

 // Use swgAS
 use swgAS\controllers\baseController;
 use swgAS\swgAdmin\models\adminloginModel;
 use swgAS\utils\security;

class adminloginController extends baseController
{
  public function login(ServerRequestInterface $request, ResponseInterface $response)
  {
      $adminLogin = new adminloginModel();

      $login = $adminLogin->authLogin(array(
                  "mongodb" => $this->getCIElement('mongodb'),
                  "errorLogger" => $this->getCIElement('swgErrorLog'),
                  "adminLogger" => $this->getCIElement('adminLog'),
                  "adminLockLog" => $this->getCIElement('adminLockLog'),
                  "flash" => $this->getCIElement('flash'),
                  "userIP" => $this->getCIElement('userIP'),
                  "username" => $request->getParam('username'),
                  "password" => $request->getParam('password'))
      );

      if ($login === "Access Denied") {
          $this->getCIElement('flash')->addMessage("error", $login);
          return $response->withRedirect('/admin');
      }
      elseif ($this->getCIElement("flash")->getMessage("islocked")){
        return $response->withRedirect('/admin');
      }
      else {
          return $response->withRedirect('/admin/dashboard/overview');
      }
  }
}

?>
