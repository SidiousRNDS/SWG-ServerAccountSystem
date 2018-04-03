<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * Routes
 ******************************************************************/

$swgapi->group('/v1', function() use ($swgapi) {

	$swgapi->post('/token', \swgAPI\controllers\tokenController::class . ':getToken');

	$swgapi->get('/test', function($request, $response, $args) {
		$decode = $this->JwToken;
		print_r($decode);
	});

	$swgapi->group('/account', function() use ($swgapi) {

		// GET - Get Accounts
		$swgapi->post('/getaccount', \swgAPI\controllers\accountController::class . ':getAccount');

		// POST - Check Account
		$swgapi->post('/checkaccount', \swgAPI\controllers\accountController::class . ':checkAccount');

		// POST - Create Account
		$swgapi->post('/addaccount', \swgAPI\controllers\accountController::class . ':addAccount');

	});

});

?>