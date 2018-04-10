<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 09 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAPI\controllers
 * CLASS: serverstatusController
 ******************************************************************/

namespace swgAS\swgAPI\controllers;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\controllers\baseController;
use swgAS\swgAPI\models\serverstatusModel;

class serverstatusController extends baseController
{
    /**
     * Summary lastSevenDays - Get the last seven days of records
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function lastSevenDays(ServerRequestInterface $request, ResponseInterface $response)
    {
        $ss = new serverstatusModel();

        $status = $ss->getLastSevenDays(array(
                "mongodb"=>$this->getCIElement('mongodb'),
                "errorlogger"=>$this->getCIElement('swgErrorLog'),
                "apiLogger"=>$this->getCIElement('swgAPILog'))
        );

        return $response->withJson($status,200);
    }
}