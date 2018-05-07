<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\models
 * CLASS: characterbansModel
 ******************************************************************/

namespace swgAS\swgAPI\models;

// Use
use swgAS\helpers\errorcodes;

/**
 * Summary of characterbansModel
 */

class characterbansModel extends \Illuminate\Database\Eloquent\Model
{
	protected $table = "character_bans";
	protected $primary_key = "account_id";
}

?>
