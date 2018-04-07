<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 06 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\middelware
 * CLASS: adminmiddleware
 ******************************************************************/

namespace swgAS\swgAdmin\middelware;

// Use
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// swgAS Use
use swgAS\utils\usersessions;

class adminauthmiddleware
{
    private $adminSession = "swgASA";
    public $args;

    public function __construct($args)
    {
        $this->args = $args;

    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if($_SESSION[$this->adminSession])
        {
            $this->args['sessionID'] = $_SESSION[$this->adminSession];

            $userSession = new usersessions();

            $checkSession = $userSession->checkValidUserSession($this->args);

            if($checkSession === true)
            {
                $this->args['flash']->addMessage("error","Invalid Session - Please login");
                return $response->withRedirect('/admin');
            }

            return $response = $next($request, $response);
        }
    }
}