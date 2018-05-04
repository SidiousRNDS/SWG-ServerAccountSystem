<?php

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

namespace swgAS\helpers\messaging;

class statusmsg
{
	/**
	 * Summary of $accountStatus
	 * @var mixed
	 */
	protected static $accountStatus = [
		"created" =>  "Account for ::USERNAME:: has been created.",
		"checkspassed" => "All checks passed"
	];

	protected static $security = [
		"lremoved" => "Lock has been removed"
	];

    protected static $authcodeStatus = [
        "authcreated" => "Authcode ::AUTHCODE:: for ::USERNAME:: was created",
		"accountnotfound" => "Account not found"
    ];

    protected static $roleStatus = [
    	"rolecreated" => "Role ::ROLENAME:: has been created",
		"roleupdated" => "Role ::ROLENAME:: has been updated",
		"roledeleted" => "Role ::ROLENAME:: has been deleted"
	];

    protected static $userStatus = [
    	"usercreated" => "User ::USERNAME:: has been created",
		"userupdated" => "User ::USERNAME:: has been updated",
		"userdeleted" => "User ::USERNAME:: has been deleted"
	];

    protected static $patchStatus = [
    	"serverpatchcreated" => "Server patch ::PATCHNAME:: has been created"
	];

	/**
	 * Summary of getStatusMsg
	 * @param string $code
	 * @param string $model
	 * @return array
	 */
	public static function getStatusMsg(string $code, string $model ) : string
	{
		$status = "unknown";

		if ($code != "") {
			switch($model)
			{
				case "accountModel":
					$status = self::$accountStatus[$code];
					break;
                case "security":
                    $status = self::$security[$code];
                    break;
				case "authcodeModel":
					$status = self::$authcodeStatus[$code];
					break;
                case "adminroleModel":
                    $status = self::$roleStatus[$code];
                    break;
                case "adminusersModel":
                    $status = self::$userStatus[$code];
                    break;
                case "adminserverupdatesModel":
                case "admingameupdatesutilsModel":
					$status = self::$patchStatus[$code];
					break;
			}
		}

		return $status;
	}
}

?>
