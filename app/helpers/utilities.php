<?php

/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\utils
 * CLASS: utilities
 ******************************************************************/

namespace swgAS\helpers;

/**
 * Class utilities
 * @package swgAS\helpers
 */
class utilities
{
	/**
	 * @method getClientIp
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
	 * @method replaceStatusMsg
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
	 * @method obscureData
     * @param string $data
     * @return string
     */
	public static function obscureData(string $data) : string
    {
        return base64_encode($data);
    }

    /**
	 * @method unobscureData
	* @param string $data
	* @return string
	*/
    public static function unobscureData(string $data) : string
    {
        return base64_decode($data);
    }

    /**
	 * @method in_array_r
	 * check to see if the item is in the multi dimensional array
     * @param string $item
     * @param array $array
     * @return false|int
     */
    public function in_array_r($item , $array){
        return preg_match('/"'.preg_quote($item, '/').'"/i' , json_encode($array));
    }


    /**
	 * @method sanitizeFormData
	 * Sanitize the form data before it gets passed to a DB
     * @param $data
     * @param $filter
     * @return mixed
     */
    public function sanitizeFormData($data, $filter)
	{
		$tdata = trim($data);
		return filter_var($tdata, $filter);
	}
    
    /**
	 * @method md5CehckSum
	 * Create a md5 Checksum where needed
     * @param string $filePath
     * @return string
     */
	public static function md5CheckSum($filePath)
	{
        return md5_file($filePath);
	}
}

?>
