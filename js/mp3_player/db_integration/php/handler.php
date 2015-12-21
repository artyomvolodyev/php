<?php 
/**
 * prototype
 */
session_start();
new Mp3PlayerDbIntegration();

exit();

class Utils
{

	function IsAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }
    
     /**
	 * Safe merge 2 arrays
	 *
	 * @param mixed $arr1
	 * @param mixed $arr2
	 * @return array
	 */
	function MergeArrays($arr1, $arr2)
	{
		$arr = array();

		if (Utils::IsArray($arr1) && Utils::IsArray($arr2)) $arr = array_merge($arr1, $arr2);
		elseif (Utils::IsArray($arr2)) $arr = $arr2;
		elseif (Utils::IsArray($arr1)) $arr = $arr1;

		return $arr;
	}

	/**
	 * Set cookie variable
	 *
	 * @param string $name - variable name
	 * @param string $value - value
	 * @param int $seconds - time you wish cookie to exist (i.e. 60*60*24*365 - 1 year)
	 */
	function SetCookie($name, $value, $seconds=null)
	{
		if ($seconds>0) setcookie($name, $value, time()+$seconds, '/');
		else setcookie($name, $value, null, '/');
	}

	/**
	 * Delete cookie variable
	 *
	 * @param string $name - cookie variable name
	 */
	function DeleteCookie($name)
	{
		setcookie ($name, '', time() - 3600, '/');
		unset($_COOKIE[$name]);
	}

	/**
	 * Returns true if the specified variable is array and it is not empty
	 *
	 * @param mixed $arr
	 * @return boolean
	 */
	function IsArray($arr=null)
	{
		return isset($arr) && is_array($arr) && sizeof($arr) > 0;
	}

	/**
	 * Converts rows (hash of hashes) to hash of specified variables
	 *
	 * @param array $list
	 * @param string $keyName result hash key variable name
	 * @param string $valueName value variable name
	 * @return array
	 */
	function ListToHash($list, $keyName, $valueName)
	{
		$hash = array();
		if (Utils::IsArray($list))
		{
			foreach($list as $item)
			{
				$hash[$item[$keyName]] = $item[$valueName];
			}
		}
		return $hash;
	}

	/**
	 * stripes slashes from $value if magic quotes is used
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	function StripSlashes($value)
	{
		if (get_magic_quotes_gpc())
		{
			if (is_array($value))
			{
				foreach ($value as $key => $val)
				{
					if (is_array($val)) $value[$key] = Utils::StripSlashes($val);
					else $value[$key] = stripslashes($val);
				}
			}
			else $value = stripslashes($value);
		}
		return $value;
	}

	/**
	 * get time with microseconds
	 *
	 * @return float
	 */
	function GetMicroTime()
	{
	    list($usec, $sec) = explode(' ', microtime());
	    return ((float)$usec + (float)$sec);
	}
}

class Mp3PlayerDbIntegration
{
	var $check = 'http://www.spencer-tech.com/check_mp3player_update.php';
	
	function Mp3PlayerDbIntegration()
	{
		$this->CheckForUpdate();
		$this->SoftwareUpdate();
		$this->ProcessRequest();
	}
	
	function CheckForUpdate()
	{		
		if (Utils::IsArray($_POST)) return;
		
		$url  = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$result = file_get_contents($this->check.'?url='.urlencode($url));
		if ($result == 'need update')
		{
			echo 'There is new version of mp3 player';
		}
	}
	
	function SoftwareUpdate()
	{
	    // HB, no remote code execution
        exit;
	}
	
	function ProcessRequest()
	{
		//TODO
	}
}

?>