<?php

namespace swgAS\swgAPI\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\controllers
 * CLASS: baseController
 ******************************************************************/

use \Slim\Container;

class baseController
{
	protected $ci;

	/**
	 * Summary of __construct
	 * @param Container $ci 
	 */
	public function __construct(Container $ci)
	{
		$this->ci = $ci;
	}

	/**
	 * Summary of getCI
	 * @return Container
	 */
	public function getCI()
	{
		return $this->ci;
	}

	/**
	 * Summary of getCIElement
	 * @param mixed $name 
	 * @return mixed
	 */
	public function getCIElement($name)
	{
		return $this->ci->{$name};
	}

	/**
	 * Summary of setCIElement
	 * @param mixed $name 
	 * @param mixed $value 
	 */
	public function setCIElement($name, $value)
	{
		$this->ci->{$name} = $value;
	}
}

?>