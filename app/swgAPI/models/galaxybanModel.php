<?php

namespace swgAPI\models;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\models
 * CLASS: galaxybanModel
 ******************************************************************/

// Use
use \swgAPI\utils\errorcodes;

/**
 * Summary of galaxybanModel
 */
class galaxybanModel extends \Illuminate\Database\Eloquent\Model
{
	protected $table = "galaxy_bans";
	protected $primary_key = "account_id";
}

?>