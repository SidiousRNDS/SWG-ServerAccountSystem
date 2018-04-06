<?php

namespace swgAS\utils;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\utils
 * CLASS: password
 ******************************************************************/

// Use

// swgAS Use
use swgAS\config\settings;
use swgAS\utils\errormsg;

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
		//$salt = $this->generateSalt();
		$passHash = hash(settings::CRYPTHASH, settings::PWSECRET.$args['password'].$args['salt']);

		return array('passwordHash'=>$passHash,'salt'=>$args['salt']);
	}
}

?>
