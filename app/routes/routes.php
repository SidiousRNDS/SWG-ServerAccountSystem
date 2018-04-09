<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************/

use swgAS\config\settings;
use swgAS\swgAdmin\middelware\adminauthmiddleware;


$swgAS->get('/', function ($request, $response, $args) use ($swgAS) {
    return $this->views->render($response, 'clients.twig',['uIP'=>$this->get('userIP'),'captchakey'=>settings::G_CAPTCHA_KEY]);
})->setName('home');


// Admin Group
$swgAS->group('/admin', function() use($swgAS){

    // Admin Base
    $adminBaseRoutes = ["", "/"];
    foreach($adminBaseRoutes as $adminRoutes) {
        $swgAS->get($adminRoutes, function ($request, $response, $args) use ($swgAS) {
            return $this->views->render($response, 'adminlogin.twig',['uIP'=>$this->get('userIP'), 'captchakey'=>settings::G_CAPTCHA_KEY, 'flash' => $this->flash]);
        })->add(new adminauthmiddleware($swgAS->getContainer()))->setName('adminlogin');
    }

    // Login
    $swgAS->post('/login', \swgAS\swgAdmin\controllers\adminloginController::class . ':login');

    // Dashboard Group
    $swgAS->group('/dashboard', function() use($swgAS) {

        // Dashboard Base
        $swgAS->get('', function ($request, $response, $args) use ($swgAS) {
            return $this->views->render($response, 'admindashboard.twig',['title' => 'Dashboard','route' => $request->getUri()->getPath()]);
        })->add(new adminauthmiddleware($swgAS->getContainer()))->setName('dashboard');

        // Overview
        $swgAS->get('/overview', function ($request, $response, $args) use ($swgAS) {
            return $this->views->render($response, 'adminoverview.twig',['title' => 'Overview','route' => $request->getUri()->getPath()]);
        })->add(new adminauthmiddleware($swgAS->getContainer()))->setName('overview');

        // Create authcode form
        $swgAS->get('/authcodes/createauth', function ($request, $response, $args) use ($swgAS) {
            return $this->views->render($response, 'admincreateauthcodes.twig',['title' => 'Create Authcode','route' => $request->getUri()->getPath()]);
        })->add(new adminauthmiddleware($swgAS->getContainer()))->setName('createauth');

        // Generate an authcode
        //$swgAS->post('/authcodes/genauthcode', \swgAS\swgAPI\controllers\authcodeController::class . ':adminGenerateAuthCode')->add(new adminauthmiddleware($swgAS->getContainer()))->setName('genauthcode');
    });

});


// API Group
$swgAS->group('/api', function() use ($swgAS) {

    $swgAS->post('/token', \swgAS\swgAPI\controllers\tokenController::class . ':getToken');

    // TODO - Remove before launch this is really not needed and is only for testing
    $swgAS->get('/test', function($request, $response, $args) {
        $decode = $this->JwToken;
        print_r($decode);
    });

    // Version: V1
    $swgAS->group('/v1', function() use ($swgAS) {
        $swgAS->group('/account', function() use ($swgAS) {

            // GET - Get Accounts
            $swgAS->post('/getaccount', \swgAS\swgAPI\controllers\accountController::class . ':getAccount');

            // POST - Check Account
            $swgAS->post('/checkaccount', \swgAS\swgAPI\controllers\accountController::class . ':checkAccount');

            // POST - Create Account
            $swgAS->post('/addaccount', \swgAS\swgAPI\controllers\accountController::class . ':addAccount');

        });

    });
});

?>
