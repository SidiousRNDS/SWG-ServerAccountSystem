<?php

namespace swgAPITests\swgAPI\Models;

require dirname(dirname(__FILE__)) . '../../../vendor/autoload.php';

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPITests\models
 * CLASS: accountModelTest
 ******************************************************************/

use \PHPUnit\Framework\TestCase;
use \swgAPI\config\settings;
use \swgAPI\models\accountModel;

class accountModelTest extends TestCase
{
	private static $capsule = null;


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
	}

	/**
	 * Summary of tearDown
	 */
	public function tearDown()
	{

	}

	/**
	 * Summary of testCheckAccount
	 */
	public function testCheckAccountNotFound()
	{
		$args = ['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'];
		$args['db'] = self::$capsule;

		$output = accountModel::getAccount($args);

		$this->assertContains("Account not found", $output);
	}

	/**
	 * Summary of testCheckAccountFound
	 */
	public function testCheckAccountFound()
	{
		$args = ['username'=>'2Alpha','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'];
		$args['db'] = self::$capsule;

		$output = accountModel::getAccount($args);

		$this->assertObjectHasAttribute("account_id",$output);

	}

	/**
	 * Summary of testAddAccount
	 * @param mixed $items
	 * @param mixed $expected
	 * @dataProvider providerAccountData
	 */
	public function testAddAccount($args, $expected)
	{
		$args['db'] = self::$capsule;

		$output = accountModel::addAccount($args);

		$this->assertContains($expected, $output);
	}

	public function providerAccountData()
	{
		return [
			'Active Username' => [['username'=>'2Alpha','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'],"The account you are trying to create already exists. If you think this is an error please contact the administrator"],
			'Active Email' => [['username'=>'terriallen','email'=>'ianford21@gmail.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'],"Account not created you already have the max number of accounts allowed. If you think this is and error please contact the administrator"],
			'Active IP' => [['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'50.248.205.86','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'],"Account not created you already have the max number of accounts allowed. If you think this is and error please contact the administrator"],
			'Invalid Email' => [['username'=>'terriallen','email'=>'justtesting@testing','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'], "Not a valid email"],
			'Invalid IP' => [['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'10.01','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'], "IP not valid"],
			'Invalid Authcode' => [['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-xxxxxxxxxxx','password'=>'12345','repassword'=>'12345'],"Authcode was not found"],
			'Username to long' => [['username'=>'terriallensdfasdfasdfasdfasdfasdfasdfasdfsdfasdfasdf','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'], "Username length is greater then the max length"],
			'Username to short' => [['username'=>'ter','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'12345'], "Username is shorter then required length"],
			'Password to long' => [['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345adsfasdfasdfasfasdfasdfasdfasdfsadfsadfsdfsfsdfsfasdfasdfasdfasfasfasfasdfasdfsadfasdfasdfasdfasdfasdfasdf','repassword'=>'12345adsfasdfasdfasfasdfasdfasdfasdfsadfsadfsdfsfsdfsfasdfasdfasdfasfasfasfasdfasdfsadfasdfasdfasdfasdfasdfasdf'], "Password length is greater then the max length"],
			'Password to short' => [['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12','repassword'=>'12'], "Password is shorter then required length"],
			'Missmatch Passwords' => [['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'R1-Jf8bEXf-9p7rhJZ','password'=>'12345','repassword'=>'123454'], "Password do not match"],
			'No Authcode' => [['username'=>'terriallen','email'=>'justtesting@testing.com','ip'=>'10.0.0.1','authcode'=>'','password'=>'12345','repassword'=>'12345'], "Authcode was not found"]
		];
	}
}

?>