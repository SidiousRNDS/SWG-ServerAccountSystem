<?php
/*****************************************************************
 * RNDS SWG Server System
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

/**
 * Class serverstatusController
 * @package swgAS\swgAPI\controllers
 */
class serverstatusController extends baseController
{
    /**
     * @method  lastSevenDays
     * Get the last seven days of records
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
     * @method  last24HoursLive
     * Get the last 24 hours of records
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
     * @method uniqueAccountLoginsCurrentMont
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