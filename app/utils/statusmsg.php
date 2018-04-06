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
 * CLASS: statusmsg
 ******************************************************************/

class statusmsg
{
	/**
	 * Summary of $accountStatus
	 * @var mixed
	 */
	protected static $accountStatus = [
		"created" =>  "Account for ::USERNAME:: has been created.",
		//"updated" => ["statuscode" =>201, "statusmsg"=>"Account {$username} has been updated."],
		//"preset"  => ["statuscode" =>201, "statusmsg"=>"Password for account {$username} has been reset."],
		"checkspassed" => "All checks passed"
	];

	/**
	 * Summary of getStatusMsg
	 * @param string $code
	 * @param string $model
	 * @return array
	 */
	public static function getStatusMsg(string $code, string $model ) : string
	{
		$status = [];

		if ($code != "") {
			switch($model)
			{
				case "accountModel":
					$status = self::$accountStatus[$code];
					break;
			}
		}

		return $status;
	}
}

?>
