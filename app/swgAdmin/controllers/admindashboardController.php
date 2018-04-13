<?php
/*****************************************************************
 * RNDS SWG Account System
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
use swgAS\swgAdmin\models\adminroleModel;
use swgAS\swgAPI\models\authcodeModel;
use swgAS\config\settings;


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
        return $this->getCIElement('views')->render($response, 'adminoverview.twig',[
            'title' => 'Overview',
            'route' => $request->getUri()->getPath(),
            'baseURL' => settings::BASE_URL,
            'tokenURL' => settings::TOKEN_URL,
            'statusURL' => settings::STATUS_URL
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
            'title' => 'Create Authcode',
            'route' => $request->getUri()->getPath(),
            'baseURL' => settings::BASE_URL,
            'tokenURL' => settings::TOKEN_URL,
            'statusURL' => settings::STATUS_URL,
            'numberOfAccounts' => settings::NUMBER_OF_ACCOUNTS_ALLOWED,
            'authlist' => $authcodeList,
            'primary_prefix' => settings::PRIMARY_CODE_PREFIX,
            'extended_prefix' => settings::EXTENDED_CODE_PREFIX,
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
            'title' => 'View Used Authcode',
            'route' => $request->getUri()->getPath(),
            'baseURL' => settings::BASE_URL,
            'tokenURL' => settings::TOKEN_URL,
            'statusURL' => settings::STATUS_URL,
            'numberOfAccounts' => settings::NUMBER_OF_ACCOUNTS_ALLOWED,
            'authlist' => $authcodeList,
            'primary_prefix' => settings::PRIMARY_CODE_PREFIX,
            'extended_prefix' => settings::EXTENDED_CODE_PREFIX,
        ]);
    }
}