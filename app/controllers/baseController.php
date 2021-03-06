<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\controllers
 * CLASS: baseController
 ******************************************************************/

namespace swgAS\controllers;

use \Slim\Container;

/**
 * Class baseController
 * @package swgAS\controllers
 */
class baseController
{
	protected $ci;

	/**
	 * @method __construct
	 * @param Container $ci
	 */
	public function __construct(Container $ci)
	{
		$this->ci = $ci;
	}

	/**
	 * @method getCI
	 * @return Container
	 */
	public function getCI()
	{
		return $this->ci;
	}

	/**
	 * @method getCIElement
	 * @param mixed $name
	 * @return mixed
	 */
	public function getCIElement($name)
	{
		return $this->ci->{$name};
	}

	/**
	 * @method setCIElement
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function setCIElement($name, $value)
	{
		$this->ci->{$name} = $value;
	}
}