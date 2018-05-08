<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\utils
 * CLASS: password
 ******************************************************************/

namespace swgAS\helpers;

// Use

// swgAS Use
use swgAS\config\settings;
use swgAS\helpers\messaging\errormsg;

/**
 * Class password
 * @package swgAS\helpers
 */
class password
{
    /**
	 * @method getSalt
     * @return string
     * @throws \ReflectionException
     */
	public function getSalt()
	{
		return $this->generateSalt();
	}

    /**
	 * @method generateSalt
     * @return string
     * @throws \ReflectionException
     */
	private function generateSalt()
	{
		$salt = base64_encode(openssl_random_pseudo_bytes(settings::OPENSSLBYTES_LENGTH));
		if(!$salt)
		{
			return messaging\errormsg::getErrorMsg("salt",(new \ReflectionClass(self::class))->getShortName());
		}

		return $salt;
	}

    /**
	 * @method generateEncryptedPassword
     * @param array $args
     * @return array
     */
	public function generateEncryptedPassword($args) : array
	{
		//$salt = $this->generateSalt();
		$passHash = hash(settings::CRYPTHASH, settings::PWSECRET.$args['password'].$args['salt']);

		return array('passwordHash'=>$passHash,'salt'=>$args['salt']);
	}
}
