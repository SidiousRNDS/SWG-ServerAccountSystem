<?php
    /*****************************************************************
     * RNDS
     * @author : Sidious <sidious@rnds.io>
     * @since  : 03 May 2018
     * @link   : https://github.com/SidiousRNDS/
     * @version: 1.0.0
     *****************************************************************
     * NAMESPACE: swgAPI\controllers
     * CLASS: patchController
     *****************************************************************/
    
    namespace swgAPI\controllers;
    
    // Use
    use \Psr\Http\Message\ServerRequestInterface;
    use \Psr\Http\Message\ResponseInterface;

    // Use swgAS
    use swgAS\controllers\baseController;
    use swgAS\swgAdmin\models\adminserverupdatesModel;
    
    class patchController extends baseController
    {
    
        /**
         * Summary getServerPatchNotes - Get all the notes for the server and pass it back as a json_object
         * @param ServerRequestInterface $request
         * @param ResponseInterface      $response
         * @return string
         */
        public function getServerPatchNotes(ServerRequestInterface $request, ResponseInterface $response)
        {
            $serverPatchNotes = new adminserverupdatesModel();
            
            return $serverPatchNotes->getServerPatches(['mongodb' => $this->getCIElement('mongodb')]);
        }
    }