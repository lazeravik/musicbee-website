<?php
/**
 * Copyright (c) 2016 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *  
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 *
 */

class MailManager
{

	public static function sendMail($to, $from, $charset, $content_type, $subject, $message, $bindedval)
	{
		$headers = "From: ".$from."\r\n";
		$headers .= "Reply-To: ".$from."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: ".$content_type."; charset=".$charset."\r\n";

		//now assign values to enclosed parameters in the message
		$finalMsg = strtr($message, $bindedval);


		try
		{
			if(mail($to, $subject, $finalMsg, $headers))
				return true;
		}
		catch (Exception $e)
		{
			return false;
		}

		return false;
	}

	public static function getAdminEmailList()
	{
		global $connection, $db_info;
		if(databaseConnection())
		{
			try
			{
				$sql = "SELECT * FROM {$db_info['member_tbl']} WHERE (rank = 1 OR rank = 2)";
				$statement = $connection->prepare($sql);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return $result;
				} else {
					return null;
				}
			} catch(Exception $e) {

			}
		}
		return null;
	}
}