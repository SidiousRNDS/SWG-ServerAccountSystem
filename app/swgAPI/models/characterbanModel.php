<?php
namespace swgAS\swgAPI\models;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\models
 * CLASS: characterbansModel
 ******************************************************************/

// Use
use swgAS\swgAPI\utils\errorcodes;

/**
 * Summary of characterbansModel
 */

class characterbansModel extends \Illuminate\Database\Eloquent\Model
{
	protected $table = "character_bans";
	protected $primary_key = "account_id";
}

?>