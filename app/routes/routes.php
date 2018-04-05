<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************/

use swgAS\config\settings;

$swgAS->get('/', function ($request, $response, $args) use ($swgAS) {
    return $this->views->render($response, 'clients.twig',['uIP'=>$this->get('userIP'),'captchakey'=>settings::G_CAPTCHA_KEY]);
})->setName('home');

/*$swgAS->get('/info', function($request, $responce, $args) use ($swgAS) {
    echo phpInfo();
});*/

$swgAS->group('/admin', function() use($swgAS){
    $adminBaseRoutes = ["", "/"];
    foreach($adminBaseRoutes as $adminRoutes) {
        $swgAS->get($adminRoutes, function ($request, $response, $args) use ($swgAS) {
            return $this->views>render($response, 'adminlogin.twig',['captchakey'=>settings::G_CAPTCHA_KEY]);
        });
    }
});


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
