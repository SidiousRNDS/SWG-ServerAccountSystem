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
use \PHPUnit\Framework\Constraint\IsType;
use \swgAPI\utils\password;
use \swgAPI\utils\validation;

class passwordTest extends TestCase
{
	private $password = null;

	/**
	 * Summary of setUp
	 */
	public function setUp()
	{
		$this->password = new password();
	}

	/**
	 * Summary of tearDown
	 */
	public function tearDown()
	{
		unset($this->password);
	}

	/**
	 * Summary of testGenerateSaltReturnSalt
	 */
	public function testGenerateSaltReturnSaltString()
	{
		$output = $this->password->getSalt();
		$this->assertInternalType(IsType::TYPE_STRING, $output, "Returned a " . gettype($output) . " instead of string");
	}

	/**
	 * Summary of testGenerateEncryptedPasswordReturnPasswordString
	 */
	public function testGenerateEncryptedPasswordReturnPasswordString()
	{
		$args = ['password'=>'123456'];
		$output = $this->password->generateEncryptedPassword($args);

		$this->assertArrayHasKey("passwordHash",$output);
		$this->assertArrayHasKey("salt",$output);
	}

	/**
	 * Summary of testValidateEncryptedPassword
	 */
	public function testValidateEncryptedPassword()
	{
		$args = ['password'=>'123456'];
		$passArr = $this->password->generateEncryptedPassword($args);

		$args['passwordHash'] = $passArr['passwordHash'];
		$args['salt'] = $passArr['salt'];

		$output = validation::validateEncryptedPassword($args);

		$this->assertTrue($output);
	}
}
?>
