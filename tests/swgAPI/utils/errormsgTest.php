<?php


namespace swgAPITests\swgAPI\utils;

require dirname(dirname(__FILE__)) . '../../../vendor/autoload.php';

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPITests\utils
 * CLASS: errormsgTest
 ******************************************************************/

use \PHPUnit\Framework\TestCase;
use \swgAPI\utils\errormsg;

class errormsgTest extends TestCase
{
	/**
	 * Summary of testgetErrorMsg
	 * @param mixed $args
	 * @param mixed $expected
	 * @dataProvider providerErrorMsgData
	 */
	public function testgetErrorMsg($args, $expected)
	{
		$output = errormsg::getErrorMsg($args["code"], $args["model"]);
		$this->assertContains($expected, $output);
	}

	public function providerErrorMsgData()
	{
		return [
			"Check To Many Accounts - Account Model" => [["code"=>"tomanyaccounts","model"=>"accountModel"],"Account not created you already have the max number of accounts allowed. If you think this is and error please contact the administrator"],
			"Check to see if we can talk to the DB for valdidate Auth Username" => [["code"=>"validateauthusername","model"=>"authcodeModel"],"Could not access the db for validateAuthUsername"]
		];
	}
}

?>