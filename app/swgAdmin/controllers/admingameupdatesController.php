<?php
    /*****************************************************************
     * RNDS SWG Account System
     * @author: Sidious <sidious@rnds.io>
     * @since: 30 April 2018
     * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
     * @version: 1.0.0
     ******************************************************************
     * NAMESPACE: swgAS\swgAdmin\controllers
     * CLASS: admingameupdatesController
     ******************************************************************/
    
    namespace swgAS\swgAdmin\controllers;

    // Use
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;

// Use swgAS
    use swgAS\controllers\baseController;
    use swgAS\swgAdmin\models\admingameupdatesModel;
    use swgAS\config\settings;
    use swgAS\helpers\security;
    
    class admingameupdatesController extends baseController
    {
        
        public function adminGameUpdatesServerPatchCreateView(ServerRequestInterface $request, ResponseInterface $response)
        {
            return $this->getCIElement('views')->render($response, 'admincreateserverupdates.twig', [
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Server Patch',
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms'])
            ]);
        }
    
        public function adminGameUpdatesServerPatchCreateAction(ServerRequestInterface $request, ResponseInterface $response)
        {
        
        }
    
        public function adminGameUpdatesServerPatchView(ServerRequestInterface $request, ResponseInterface $response)
        {
        
        }
    
        public function adminGameUpdatesLauncherPatchCreateView(ServerRequestInterface $request, ResponseInterface $response)
        {
            return $this->getCIElement('views')->render($response, 'admincreatelauncherupdates.twig', [
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Launcher Patch  ',
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms'])
            ]);
        }
        
        public function adminGameUpdatesLauncherPatchCreateViewAction(ServerRequestInterface $request, ResponseInterface $response)
        {
        
        }
    
        public function adminGameUpdatesLauncherPatchView(ServerRequestInterface $request, ResponseInterface $response)
        {
        
        }
    }