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
    public function lastSevenDaysLive(ServerRequestInterface $request, ResponseInterface $response)
    {
        $ss = new serverstatusModel();

        $status = $ss->getLastSevenDaysLive([
                "mongodb"=>$this->getCIElement('mongodb'),
                "errorlogger"=>$this->getCIElement('swgErrorLog'),
                "apiLogger"=>$this->getCIElement('swgAPILog')
        ]);

        return $response->withJson($status,200);
    }

    /**
     * Summary last24HoursLive - Get the last 24 hours of records
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function last24HoursLive(ServerRequestInterface $request, ResponseInterface $response)
    {
        $ss = new serverstatusModel();

        $status = $ss->getlast24HoursLive([
                "mongodb"=>$this->getCIElement('mongodb'),
                "errorlogger"=>$this->getCIElement('swgErrorLog'),
                "apiLogger"=>$this->getCIElement('swgAPILog')
        ]);

        return $response->withJson($status,200);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function uniqueAccountLoginsCurrentMonth(ServerRequestInterface $request, ResponseInterface $response)
    {
        $ss = new serverstatusModel();

        $status = $ss->getUniqueAccounts([
            "db"=>$this->getCIElement('db'),
            "errorlogger"=>$this->getCIElement('swgErrorLog'),
            "apiLogger"=>$this->getCIElement('swgAPILog')
        ]);

        return $response->withJson($status,200);
    }
}