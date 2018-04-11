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
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\controllers\baseController;
use swgAS\swgAPI\models\authcodeModel;
use swgAS\config\settings;


class admindashboardController extends baseController
{

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
            'authlist' => $authcodeList,
            'primary_prefix' => settings::PRIMARY_CODE_PREFIX,
            'extended_prefix' => settings::EXTENDED_CODE_PREFIX,
        ]);
    }
}