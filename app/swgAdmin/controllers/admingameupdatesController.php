<?php
    /*****************************************************************
     * RNDS SWG Server System
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
    use swgAS\config\settings;
    use swgAS\swgAdmin\models\gameupdates\adminserverupdatesModel;

    /**
     * Class admingameupdatesController
     * @package swgAS\swgAdmin\controllers
     */
    class admingameupdatesController extends baseController
    {

        /**
         * @method adminGameUpdateServerPatchCreateView
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         */
        public function adminGameUpdatesServerPatchCreateView(ServerRequestInterface $request, ResponseInterface $response)
        {
            return $this->getCIElement('views')->render($response, 'admincreateserverpatch.twig', [
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Server Patch',
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms']),
                'useAuth' => settings::USE_AUTHCODES,
                'liveserver'=>settings::LIVE_GAME_SERVER,
                'testserver'=>settings::TEST_GAME_SERVER
            ]);
        }

        /**
         * @method adminGameUpdatesServerPatchCreateAction
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         * @throws \ReflectionException
         */
        public function adminGameUpdatesServerPatchCreateAction(ServerRequestInterface $request, ResponseInterface $response)
        {
            $serverPatch = new adminserverupdatesModel();

            $serverPatch->addServerPatch([
                'mongodb' => $this->getCIElement('mongodb'),
                'flash' => $this->getCIElement('flash'),
                'file' => $request->getUploadedFiles(),
                'request'=>$request->getParsedBody()
            ]);

            return $this->getCIElement('views')->render($response, 'admincreateserverpatch.twig', [
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Server Patch',
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms']),
                'useAuth' => settings::USE_AUTHCODES,
                'liveserver'=>settings::LIVE_GAME_SERVER,
                'testserver'=>settings::TEST_GAME_SERVER
            ]);
        }

        /**
         * @method adminGameUpdatesServerPatchView
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         */
        public function adminGameUpdatesServerPatchView(ServerRequestInterface $request, ResponseInterface $response)
        {
            $serverPatch = new adminserverupdatesModel();
            $getPatches =  $serverPatch->getServerPatches([
                'mongodb' => $this->getCIElement('mongodb'),
                'flash'=>$this->getCIElement('flash'),
            ]);

            return $this->getCIElement('views')->render($response, 'adminviewsererpatches.twig', [
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
         * @method adminGameUpdatesServerPatchDetailView
         * @param ServerRequestInterface $request
         * @param ResponseInterface      $response
         * @return mixed
         */
        public function adminGameUpdatesServerPatchDetailView(ServerRequestInterface $request, ResponseInterface $response)
        {
            $route = $request->getAttribute('route');
            $args= $route->getArguments();
            
            $serverPatch = new adminserverupdatesModel();
            $getPatch = $serverPatch->getServerPatchById([
                'mongodb' => $this->getCIElement('mongodb'),
                'flash'=>$this->getCIElement('flash'),
                'id' => $args['id']
            ]);
            
            
            return $this->getCIElement('views')->render($response, 'adminupdateserverpatch.twig', [
                'mongodb' => $this->getCIElement('mongodb'),
                'flash'=>$this->getCIElement('flash'),
                'title' => 'Server Patch',
                'patches' => json_decode($getPatch),
                'route' => $request->getUri()->getPath(),
                'userRole' => unserialize($_SESSION['role']),
                'userPerms' => unserialize($_SESSION['perms']),
                'useAuth' => settings::USE_AUTHCODES
            ]);
        }

        /**
         * @method adminGameUpdateServerPatchUpdateAction
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         * @throws \ReflectionException
         */
        public function adminGameUpdateServerPatchUpdateAction(ServerRequestInterface $request, ResponseInterface $response)
        {
            $serverPatch = new adminserverupdatesModel();
            $serverPatch->updateServerPatch([
                'mongodb' => $this->getCIElement('mongodb'),
                'flash'=>$this->getCIElement('flash'),
                'request'=>$request->getParsedBody()
            ]);

            $uri = $request->getURI()->withPath($this->getCIElement('router')->pathFor('viewserverpatches'));

            return $response->withRedirect($uri);
        }

        public function adminGameUpdateServerPatchDelete(ServerRequestInterface $request, ResponseInterface $response)
        {
            $route = $request->getAttribute('route');
            $args= $route->getArguments();


            $serverPatch = new adminserverupdatesModel();

            $serverPatch->deleteServerPatch([
                'mongodb' => $this->getCIElement('mongodb'),
                'flash'=>$this->getCIElement('flash'),
                'id'=>$args['id']
            ]);

            $uri = $request->getURI()->withPath($this->getCIElement('router')->pathFor('viewserverpatches'));

            return $response->withRedirect($uri);
        }

        /**
         * @method adminGameUpdatesLauncherPatchCreateView
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @return mixed
         */
        public function adminGameUpdatesLauncherPatchCreateView(ServerRequestInterface $request, ResponseInterface $response)
        {
            return $this->getCIElement('views')->render($response, 'admincreatelauncherpatch.twig', [
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