<?php

	require 'vendor/autoload.php';

//	$collection = (new MongoDB\Client("mongodb://127.0.0.1:27017"))->dbname->coll;
//	var_dump($collection);


	use MongoDB\Driver\Manager;
	use MongoDB\Driver\Command;
	use MongoDB\Database;

	$db_name = "testingDB";

	$manager = new Manager("mongodb://127.0.0.1");
	//$database = new Database($manager, "testingDB");
	//var_dump($database->createCollection("testCollection"));


	$cmd = new Command(['listCollections' => 1]);
	$res = $manager->executeCommand($db_name, $cmd);

	var_dump($res);
?>
