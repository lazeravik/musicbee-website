<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from phred and other community memebers.
 */

/**
* Handles User input validation
* @author : AvikB;
*/

class Validation
{
//check if the file/image exists in remote location
public static function checkRemoteFile($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(curl_exec($ch)!==FALSE)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

//Character limit validation for user inputs
public static function charLimit($input, $limit)
	{
		if (strlen($input) <= $limit) {
			return true;
		} else {
			return false;
		}
	}
//array limit validation, used for user inputs that use commas for storing multiple values
public static function arrayLimit($input, $limit)
	{
		$inputArray = explode(",", $input);
		if (count($inputArray) <= $limit) {
			return true;
		} else {
			return false;
		}
	}


}
?>