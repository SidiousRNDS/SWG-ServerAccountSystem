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
 * CLASS: adminController
 ******************************************************************/
 // Use
 use \Psr\Http\Message\ServerRequestInterface;
 use \Psr\Http\Message\ResponseInterface;

 // Use swgAS
 use swgAS\controllers\baseController;
 use swgAS\swgAdmin\models\adminloginModel;
 use swgAS\config\settings;

class adminController extends baseController
{

    public function adminIndex(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->getCIElement('views')->render($response, 'adminlogin.twig',[
            'uIP'=>$this->getCIElement('userIP'),
            'captchakey'=>settings::G_CAPTCHA_KEY,
            'flash' => $this->getCIElement('flash')
        ]);
    }

    public function adminLogin(ServerRequestInterface $request, ResponseInterface $response)
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
