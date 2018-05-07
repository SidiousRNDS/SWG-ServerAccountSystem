<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgAPI\models
 * CLASS: galaxybanModel
 ******************************************************************/

namespace swgAS\swgAPI\models;

// Use
use swgAS\helpers\errorcodes;

/**
 * Summary of galaxybanModel
 */
class galaxybanModel extends \Illuminate\Database\Eloquent\Model
{
	protected $table = "galaxy_bans";
	protected $primary_key = "account_id";
}

?>
