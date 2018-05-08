<?php
/*****************************************************************
 * RNDS SWG Server System
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
use swgAS\helpers\sessions;
use swgAS\helpers\messaging\errormsg;
use swgAS\helpers\messaging\statusmsg;
use swgAS\helpers\security;
use swgAS\helpers\utilities;

/**
 * Class adminauthmiddleware
 * @package swgAS\swgAdmin\middelware
 */
class adminauthmiddleware
{
    /**
     * @var string
     */
    private $adminSession = "swgASA";
    /**
     * @var
     */
    public $args;

    /**
     * @method __construct
     * adminauthmiddleware constructor.
     * @param $args
     */
    public function __construct($args)
    {
        $this->args = $args;

    }

    /**
     * @method __invoke
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return mixed
     * @throws \ReflectionException
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        if($request->getUri()->getPath() != "/admin") {
            if (isset($_SESSION[$this->adminSession])) {
                $this->args['sessionID'] = $_SESSION[$this->adminSession];

                $userSession = new sessions();

                $checkSession = $userSession->checkValidUserSession($this->args);

                if ($checkSession === true) {
                    $this->args['flash']->addMessage("error", errormsg::getErrorMsg("invalidsession", (new \ReflectionClass(self::class))->getShortName())); //"Invalid Session");
                    return $response->withRedirect('/admin');
                }

                return $response = $next($request, $response);
            } else {
                return $response->withRedirect('/admin');
            }
        }

        // Check if there is a lock on the account
        $security = new security();
        $lock = $security->checkLocks($this->args);

        if($lock != "")
        {
            $lockMsg = errormsg::getErrorMsg("tomanyattempts", (new \ReflectionClass(self::class))->getShortName());
            $lockMsg = utilities::replaceStatusMsg( $lockMsg, "::LIMIT::",$lock->currentLockTimer);
            $lockMsg = utilities::replaceStatusMsg( $lockMsg, "::IP::",$lock->ip);

            $security->addLockMessage($this->args, $lockMsg);

            return $response = $next($request, $response);
        }

        return $response = $next($request, $response);
    }
}
