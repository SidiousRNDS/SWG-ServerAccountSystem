<?php

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

namespace swgAS\helpers;

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

    /**
	* @param string $data
	* @return string
	*/
    public static function unobscureData(string $data) : string
    {
        return base64_decode($data);
    }

    /**
	 * Summary in_array_r  check to see if the item is in the multi dimensional array
     * @param $item
     * @param $array
     * @return false|int
     */
    public function in_array_r($item , $array){
        return preg_match('/"'.preg_quote($item, '/').'"/i' , json_encode($array));
    }


    /**
	 * Summary sanitizeFormData - Sanitize the form data before it gets passed to a DB
     * @param $data
     * @param $filter
     * @return mixed
     */
    public function sanitizeFormData($data, $filter)
	{
		$tdata = trim($data);
		return filter_var($tdata, $filter);
	}
}

?>
