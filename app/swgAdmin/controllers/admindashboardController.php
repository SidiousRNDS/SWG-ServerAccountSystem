<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 10 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\controllers
 * CLASS: admindashboardController
 ******************************************************************/

namespace swgAS\swgAdmin\controllers;

// Use
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\controllers\baseController;
use swgAS\swgAPI\models\authcodeModel;
use swgAS\config\settings;
use swgAS\helpers\security;


class admindashboardController extends baseController
{

    /**
     * Summary adminDashboardIndex - Default location
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function adminDashboardIndex(ServerRequestInterface $request, ResponseInterface $response)
    {
        $security = new security();
        $userRole = $security-> loggedInUserRole([
            'mongodb' => $this->getCIElement('mongodb')
        ]);

        return $this->getCIElement('views')->render($response, 'adminoverview.twig',[
            'title' => 'Overview',
            'route' => $request->getUri()->getPath(),
            'baseURL' => settings::BASE_URL,
            'tokenURL' => settings::TOKEN_URL,
            'statusURL' => settings::STATUS_URL,
            'userRole' => unserialize($_SESSION['role']),
            'userPerms' => unserialize($_SESSION['perms']),
            'useAuth' => settings::USE_AUTHCODES
        ]);
    }

    /**
     * Summary adminCreateAuthCode - Create authorization code
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \ReflectionException
     */
    public function adminCreateAuthCode(ServerRequestInterface $request, ResponseInterface $response)
    {
        $authcodeList = authcodeModel::getActiveAuthcodes(array(
                'db'=>$this->getCIElement('db'),
                'errorlogger'=>$this->getCIElement('swgErrorLog'),
                'flash'=>$this->getCIElement('flash'))
        );

        return $this->getCIElement('views')->render($response, 'admincreateauthcodes.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title' => 'Authentication Code',
            'route' => $request->getUri()->getPath(),
            'baseURL' => settings::BASE_URL,
            'tokenURL' => settings::TOKEN_URL,
            'statusURL' => settings::STATUS_URL,
            'numberOfAccounts' => settings::NUMBER_OF_ACCOUNTS_ALLOWED,
            'authlist' => $authcodeList,
            'primary_prefix' => settings::PRIMARY_CODE_PREFIX,
            'extended_prefix' => settings::EXTENDED_CODE_PREFIX,
            'userRole' => unserialize($_SESSION['role']),
            'userPerms' => unserialize($_SESSION['perms']),
            'useAuth' => settings::USE_AUTHCODES
        ]);
    }

    /**
     * Summary adminViewUsedAuthCodes - View all used authorization codes
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function adminViewUsedAuthCodes(ServerRequestInterface $request, ResponseInterface $response)
    {
        $authcodeList = authcodeModel::getUsedAuthcodes(array(
                'db'=>$this->getCIElement('db'),
                'errorlogger'=>$this->getCIElement('swgErrorLog'),
                'flash'=>$this->getCIElement('flash'))
        );
        
        return $this->getCIElement('views')->render($response, 'adminviewauthcodes.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title' => 'View Used Authentication Code',
            'route' => $request->getUri()->getPath(),
            'baseURL' => settings::BASE_URL,
            'tokenURL' => settings::TOKEN_URL,
            'statusURL' => settings::STATUS_URL,
            'numberOfAccounts' => settings::NUMBER_OF_ACCOUNTS_ALLOWED,
            'authlist' => $authcodeList,
            'primary_prefix' => settings::PRIMARY_CODE_PREFIX,
            'extended_prefix' => settings::EXTENDED_CODE_PREFIX,
            'userRole' => unserialize($_SESSION['role']),
            'userPerms' => unserialize($_SESSION['perms']),
            'useAuth' => settings::USE_AUTHCODES
        ]);
    }
}