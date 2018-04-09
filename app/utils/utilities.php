<?php

namespace swgAS\utils;


/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\utils
 * CLASS: utilities
 ******************************************************************/

class utilities
{
	/**
	 * Summary of getClientIp
	 * @return mixed
	 */
	public static function getClientIp() {

		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED']))
		{
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		}
		elseif (isset($_SERVER['HTTP_FORWARDED_FOR']))
		{
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		}
		elseif (isset($_SERVER['HTTP_FORWARDED']))
		{
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		}
		elseif (isset($_SERVER['REMOTE_ADDR']))
		{
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}
		else {
			$ipaddress = 'UNKNOWN';
		}

		return self::obscureData($ipaddress);
	}

	/**
	 * Summary of replaceStatusMsg
	 * @param string $msg
	 * @param string $lookFor
	 * @param string $replaceWith
	 * @return mixed
	 */
	public static function replaceStatusMsg(string $msg, string $lookFor, string $replaceWith) : string
	{
		return preg_replace('/'.$lookFor.'/', $replaceWith, $msg);
	}

    /**
     * @param string $data
     * @return string
     */
	public static function obscureData(string $data) : string
    {
        return base64_encode($data);
    }

    public static function unobscureData(string $data) : string
    {
        return base64_decode($data);
    }
}

?>