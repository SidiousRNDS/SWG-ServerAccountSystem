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
 * CLASS: accountbanModel
 ******************************************************************/

// Use
use \swgAPI\utils\errorcodes;

/**
 * Summary of accountbanModel
 */
class accountbanModel extends \Illuminate\Database\Eloquent\Model
{
	protected $table = "account_bans";
	protected $primary_key = "account_id";
}

?>