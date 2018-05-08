<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************/

use swgAS\config\settings;
use swgAS\swgAdmin\middelware\adminauthmiddleware;

/**
 * Application routes
 */

$swgAS->get('/', function ($request, $response, $args) use ($swgAS) {
    return $this->views->render($response, 'clients.twig',['uIP'=>$this->get('userIP'),'captchakey'=>settings::G_CAPTCHA_KEY,'useAuth'=>settings::USE_AUTHCODES]);
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
    $swgAS->post('/login', \swgAS\swgAdmin\controllers\adminController::class . ':adminLogin')
        ->setName('login');

    // Dashboard Group
    $swgAS->group('/dashboard', function() use($swgAS) {

        // Dashboard Base
        $dashboardBaseRoutes = ["", "/"];
        foreach($dashboardBaseRoutes as $dashboardRoutes) {
            $swgAS->get($dashboardRoutes, \swgAS\swgAdmin\controllers\admindashboardController::class . ':adminDashboardIndex')
                ->add(new adminauthmiddleware($swgAS->getContainer()));
        }

        $swgAS->get('/overview', \swgAS\swgAdmin\controllers\admindashboardController::class . ':adminDashboardIndex')
            ->add(new adminauthmiddleware($swgAS->getContainer()))
            ->setName('overview');

        $swgAS->group('/authcodes', function() use($swgAS){

            // Create Authcode form
            $swgAS->get('/createauthview', \swgAS\swgAdmin\controllers\adminauthcodeController::class . ':adminCreateAuthCodeView')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('createauthview');

            // Generate an Authcode
            $swgAS->post('/createauthaction', \swgAS\swgAdmin\controllers\adminauthcodeController::class . ':adminCreateAuthCodeAction')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('createauthaction');

            // View all Not Used Authcodes
            $swgAS->get('/viewnotused', \swgAS\swgAdmin\controllers\adminauthcodeController::class . ':adminNotUsedAuthCodesView')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('viewnotused');

            // View all Used Authcodes
            $swgAS->get('/viewused', \swgAS\swgAdmin\controllers\adminauthcodeController::class . ':adminUsedAuthCodesView')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('viewused');

            // Update Authcode View
            $swgAS->get('/updateauthview/{id}', \swgAS\swgAdmin\controllers\adminauthcodeController::class . ':adminUpdateAuthCodeView')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('updateauthview');

            // Update Authcode Action
            $swgAS->post('/updateauthaction', \swgAS\swgAdmin\controllers\adminauthcodeController::class . ':adminUpdateAuthCodeAction')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('updateauthaction');

            // Update Authcode Action
            $swgAS->get('/deleteauth/{id}', \swgAS\swgAdmin\controllers\adminauthcodeController::class . ':adminDeleteAuthCodeAction')
                ->add(new adminauthmiddleware($swgAS->getContainer()))
                ->setName('deleteauth');
        });


        $swgAS->group('/administration', function() use($swgAS) {

            $swgAS->group('/roles',function() use($swgAS){

                // Create a Role form
                $swgAS->get('/createrole', \swgAS\swgAdmin\controllers\adminroleController::class . ':adminCreateRoleView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('createrole');

                // Create Role Action
                $swgAS->post('/createroleaction', \swgAS\swgAdmin\controllers\adminroleController::class .':adminCreateRoleAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('genrole');

                // View Roles
                $swgAS->get('/viewrole', \swgAS\swgAdmin\controllers\adminroleController::class .':adminRoleView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('viewroles');

                // Update Role form
                $swgAS->get('/updaterole/{id}', \swgAS\swgAdmin\controllers\adminroleController::class .':adminUpdateRoleView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('updaterole');

                // Update Role Action
                $swgAS->post('/updateroleaction', \swgAS\swgAdmin\controllers\adminroleController::class .':adminUpdateRoleAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('updateroleaction');

                // Delete Role Action
                $swgAS->get('/deleteroleaction/{id}', \swgAS\swgAdmin\controllers\adminroleController::class .':adminDeleteRoleAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('deleteroleaction');

            });

            $swgAS->group('/users',function() use($swgAS) {

                // Create helper form
                $swgAS->get('/createuserview', \swgAS\swgAdmin\controllers\adminuserController::class .':adminCreateUserView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('createuserview');

                // Create helper form
                $swgAS->post('/createuseraction', \swgAS\swgAdmin\controllers\adminuserController::class .':adminCreateUserAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('createuseraction');

                // View Users
                $swgAS->get('/viewusers', \swgAS\swgAdmin\controllers\adminuserController::class .':adminViewUser')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('viewusers');

                // Update User form
                $swgAS->get('/updateuserview/{id}', \swgAS\swgAdmin\controllers\adminuserController::class .':adminUpdateUserView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('updateuserview');

                // Update User Action
                $swgAS->post('/updateuseraction', \swgAS\swgAdmin\controllers\adminuserController::class .':adminUpdateUserAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('updateuseraction');

                // Delete User Action
                $swgAS->get('/deleteuseraction/{id}', \swgAS\swgAdmin\controllers\adminuserController::class .':adminDeleteUserAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('deleteuseraction');

            });
        });

        $swgAS->group('/gameupdates', function() use($swgAS){
    
            $swgAS->group('/serverpatch', function() use($swgAS) {
                $swgAS->get('/createserverpatch', \swgAS\swgAdmin\controllers\admingameupdatesController::class . ':adminGameUpdatesServerPatchCreateView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('createserverpatch');
    
                $swgAS->post('/createserverpatchaction', \swgAS\swgAdmin\controllers\admingameupdatesController::class . ':adminGameUpdatesServerPatchCreateAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('createserverpatchaction');
    
                $swgAS->get('/viewserverpatches', \swgAS\swgAdmin\controllers\admingameupdatesController::class . ':adminGameUpdatesServerPatchView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('viewserverpatches');
    
                $swgAS->get('/updateserverpatch/{id}', \swgAS\swgAdmin\controllers\admingameupdatesController::class . ':adminGameUpdatesServerPatchDetailView')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('updateserverpatch');

                $swgAS->post('/updateserverpatchaction', \swgAS\swgAdmin\controllers\admingameupdatesController::class .':adminGameUpdateServerPatchUpdateAction')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('updateserverpatchaction');

                $swgAS->post('/deleteserverpatchaction/{id}', \swgAS\swgAdmin\controllers\admingameupdatesController::class . ':adminGameUpdateServerPatchDelete')
                    ->add(new adminauthmiddleware($swgAS->getContainer()))
                    ->setName('deleteserverpatchaction');
            });
        });
    }); 

});


// API Group
$swgAS->group('/api', function() use ($swgAS) {

    $swgAS->post('/token', \swgAS\swgAPI\controllers\tokenController::class . ':getToken');

    // TODO - Remove before launch this is really not needed and is only for testing
    /*$swgAS->get('/test', function($request, $response, $args) {
        $decode = $this->JwToken;
        print_r($decode);
    });*/

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

            // POST - Server Status Last 24 Hours
            $swgAS->post('/lasttwentyfourlive', \swgAS\swgAPI\controllers\serverstatusController::class . ':last24HoursLive');

            // POST - Uniquie logins current month
            $swgAS->post('/uniqueloginscurrentmonth', \swgAS\swgAPI\controllers\serverstatusController::class . ':uniqueAccountLoginsCurrentMonth');

        });
        
        $swgAS->group('/patch', function() use($swgAS) {

            // GET - All Server Patch Notes
            $swgAS->get('/serverpatch', \swgAS\swgAPI\controllers\patchController::class  . ':getAllServerPatchNotes');

            // GET - Specific Server Patch Notes
            $swgAS->get('/serverpatch/{servername}', \swgAS\swgAPI\controllers\patchController::class  . ':getSpecificServerPatchNotes');
        });

    });
});

?>
