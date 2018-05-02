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

        /**
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         */
        public function adminGameUpdatesServerPatchCreateView(ServerRequestInterface $request, ResponseInterface $response)
        {
            return $this->getCIElement('views')->render($response, 'admincreateserverupdates.twig', [
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Server Patch',
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms']),
                'useAuth' => settings::USE_AUTHCODES
            ]);
        }

        /**
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         * @throws \ReflectionException
         */
        public function adminGameUpdatesServerPatchCreateAction(ServerRequestInterface $request, ResponseInterface $response)
        {
            $gameUpdates = new admingameupdatesModel();

            $createPatch = $gameUpdates->addServerPatch([
                'mongodb' => $this->getCIElement('mongodb'),
                'flash' => $this->getCIElement('flash'),
                'file' => $request->getUploadedFiles(),
                'request'=>$request->getParsedBody()
            ]);

            return $this->getCIElement('views')->render($response, 'admincreateserverupdates.twig', [
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Server Patch',
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms']),
                'useAuth' => settings::USE_AUTHCODES
            ]);
            
        }

        /**
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         */
        public function adminGameUpdatesServerPatchView(ServerRequestInterface $request, ResponseInterface $response)
        {
            $gameUpdates = new admingameupdatesModel();
            $getPatches = $gameUpdates->getServerPatches([
                'mongodb' => $this->getCIElement('mongodb'),
                'flash'=>$this->getCIElement('flash'),
            ]);

            return $this->getCIElement('views')->render($response, 'adminviewserverupdates.twig', [
                'mongodb' => $this->getCIElement('mongodb'),
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Server Patch',
                'patches' => json_decode($getPatches),
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms']),
                'useAuth' => settings::USE_AUTHCODES
            ]);
        }

        /**
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         */
        public function adminGameUpdatesLauncherPatchCreateView(ServerRequestInterface $request, ResponseInterface $response)
        {
            return $this->getCIElement('views')->render($response, 'admincreatelauncherupdates.twig', [
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Launcher Patch  ',
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms']),
                'useAuth' => settings::USE_AUTHCODES
            ]);
        }
        
        public function adminGameUpdatesLauncherPatchCreateViewAction(ServerRequestInterface $request, ResponseInterface $response)
        {
        
        }
    
        public function adminGameUpdatesLauncherPatchView(ServerRequestInterface $request, ResponseInterface $response)
        {
        
        }

        public function adminGameUpdatesClientPatchCreateView(ServerRequestInterface $request, ResponseInterface $response)
        {

        }

        public function adminGameUpdatesClientPatchCreateViewAction(ServerRequestInterface $request, ResponseInterface $response)
        {

        }

        public function adminGameUpdatesClientPatchView(ServerRequestInterface $request, ResponseInterface $response)
        {

        }
    }