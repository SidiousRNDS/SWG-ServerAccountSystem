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
 * CLASS: passwordTest
 ******************************************************************/

use \PHPUnit\Framework\TestCase;
use \swgAPI\utils\utilities;

class utilitiesTest extends TestCase
{

	/**
	 * Summary of setUp
	 */
	public function setUp()
	{

	}

	/**
	 * Summary of tearDown
	 */
	public function tearDown()
	{

	}

	/**
	 * Summary of testReplaceStatusMsg
	 * @param mixed $args
	 * @param mixed $expected
	 * @dataProvider providerReplaceData
	 */
	public function testReplaceStatusMsg($args, $expected)
	{

		$output = utilities::replaceStatusMsg($args['msg'], $args['lookingFor'], $args['replaceWith']);

		$this->assertContains($expected, $output);
	}

	public function providerReplaceData()
	{
		return [
			"Replace Username" => [["msg"=>"Account ::USERNAME:: has been created","lookingFor"=>"::USERNAME::","replaceWith"=>"DarthVader"],"Account DarthVader has been created"],
			"Replace Username with different lookingfor" => [["msg"=>"Account ..USERNAME.. has been created","lookingFor"=>"..USERNAME..","replaceWith"=>"DarthVader"],"Account DarthVader has been created"]
		];
	}
}
?>