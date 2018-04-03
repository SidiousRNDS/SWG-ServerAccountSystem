<?php
namespace swgAPITests\swgAPI\utls;

require dirname(dirname(__FILE__)) . '../../../vendor/autoload.php';

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPITests\models
 * CLASS: stationTest
 ******************************************************************/

use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\Constraint\IsType;
use \swgAPI\config\settings;
use \swgAPI\utils\station;

class stationTest extends TestCase
{
	private static $capsule = null;
	private $station = null;

	/**
	 * Summary of setUp
	 */
	public function setUp()
	{
		self::$capsule = new \Illuminate\Database\Capsule\Manager;
		self::$capsule->addConnection([
			"driver"		=> "mysql",
			"host"			=> settings::DBHOST,
			"database"		=> settings::DBNAME,
			"username"		=> settings::DBUSER,
			"password"		=> settings::DBPASS,
			"charset"		=> "utf8",
			"collation"		=> "utf8_general_ci",
			"prefix"		=> ""
		]);
		self::$capsule->setAsGlobal();
		self::$capsule->bootEloquent();

		$this->station = new station();
	}

	/**
	 * Summary of tearDown
	 */
	public function tearDown()
	{
		unset($this->station);
	}

	/**
	 * Summary of testCheckStationID
	 */
	public function testCheckStationIDReturnAccountId()
	{
		$args = ['station_id'=>1431755051];
		$args['db'] = self::$capsule;

		$output = $this->station->getStationId($args);
		$this->assertObjectHasAttribute("account_id",$output);
	}

	public function testGenerateStationIDReturnInt()
	{
		$args['db'] = self::$capsule;

		$output = $this->station->generateStationId($args);
		$this->assertInternalType(IsType::TYPE_INT, $output,"Returned a " . gettype($output) . " instead of int");
	}
}

?>