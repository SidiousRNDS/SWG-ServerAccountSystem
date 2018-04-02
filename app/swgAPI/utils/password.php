<?php

namespace swgAPI\utils;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\utils
 * CLASS: password
 ******************************************************************/

// Use

// swgAPI Use
use swgAPI\config\settings;
use swgAPI\utils\errormsg;

class password
{
	/**
	 * Summary of getSalt
	 * @return string
	 */
	public function getSalt()
	{
		return $this->generateSalt();
	}

	/**
	 * Summary of generateSalt
	 * @method generateSalt()
	 * @return string
	 */
	private function generateSalt()
	{
		$salt = base64_encode(openssl_random_pseudo_bytes(settings::OPENSSLBYTES_LENGTH));
		if(!$salt)
		{
			return errormsg::getErrorMsg("salt",(new \ReflectionClass(self::class))->getShortName());
		}

		return $salt;
	}

	/**
	 * Summary of generateEncryptedPassword
	 * @method generateEncyptedPassword
	 * @param mixed $args
	 * @return string
	 */
	public function generateEncryptedPassword($args) : array
	{
		$salt = $this->generateSalt();
		$passHash = hash(settings::CRYPTHASH, settings::PWSECRET.$args['password'].$salt);

		return array('passwordHash'=>$passHash,'salt'=>$salt);
	}
}

?>