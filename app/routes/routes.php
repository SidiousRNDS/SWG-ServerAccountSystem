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
        $swgAS->get($adminRoutes, \swgAS\swgAdmin\controllers\adminController::class .':adminIndex')
            ->add(new adminauthmiddleware($swgAS->getContainer()))
            ->setName('adminindex');
    }

    // Login
    $swgAS->post('/login', \swgAS\swgAdmin\controllers\adminController::class . ':adminLogin');

    // Dashboard Group
    $swgAS->group('/dashboard', function() use($swgAS) {

        // Dashboard Base
        $dashboardBaseRoutes = ["", "/", "/overview"];
        foreach($dashboardBaseRoutes as $dashboardRoutes) {
            $swgAS->get($dashboardRoutes, \swgAS\swgAdmin\controllers\admindashboardController::class . ':adminDashboardIndex')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('overview');
        }

        // Create authcode form
        $swgAS->get('/authcodes/createauth', \swgAS\swgAdmin\controllers\admindashboardController::class . ':adminCreateAuthCode')
            ->add(new adminauthmiddleware($swgAS->getContainer()))
            ->setName('createauth');

        // Generate an authcode
        $swgAS->post('/authcodes/genauthcode', \swgAS\swgAPI\controllers\authcodeController::class . ':adminGenerateAuthCode')
            ->add(new adminauthmiddleware($swgAS->getContainer()))
            ->setName('genauthcode');
    
        // View all Used Authcodes
        $swgAS->get('/authcodes/viewallused', \swgAS\swgAdmin\controllers\admindashboardController::class . ':adminViewUsedAuthCodes')
            ->add(new adminauthmiddleware($swgAS->getContainer()))
            ->setName('viewallused');
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

        $swgAS->group('/status', function() use($swgAS) {

            // POST - Server Status Last 7 Days
            $swgAS->post('/lastsevenlive', \swgAS\swgAPI\controllers\serverstatusController::class . ':lastSevenDaysLive');
        });

    });
});

?>
