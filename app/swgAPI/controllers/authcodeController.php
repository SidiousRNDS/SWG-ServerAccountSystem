<?php

namespace swgAPI\controllers;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\controllers
 * CLASS: authcodeController
 ******************************************************************/

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class authcodeController
{
	protected $ci;

	public function __construct(ContainerInterface $ci)
	{
		$this->ci = $ci;
	}
}

?>