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

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $link['root'] . 'classes/MailManager.php';
//Initalize Value
$from                = "noreply@getmusicbee.com";
$pending_addon_count = 5;
$dashboard_link      = "";
$official_link       = "";
$memberContext       = null;	//Clear any previously stored value

//Unfortunately we don't store user personal details such as email in website's database
//so get them from SMF using user ID
foreach (MailManager::getAdminEmailList() as $user)
{
	loadMemberData($user['ID_MEMBER']);
	loadMemberContext($user['ID_MEMBER']);
}

$subject = "There are ".$pending_addon_count." addons require your approval!";
$message = file_get_contents($link['root']. 'pages/mail_templates/pending.addon.dashboard.html');


//now loop through member data and put all the valid email in an array
foreach ($memberContext as $user) {
	//Make sure the emails are valid
	if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false) {
		$bindedvalarray = array(
			"{username}"              => $user['username'],
			"{pending_request_count}" => $pending_addon_count,
			"{dashboard_link}"        => $link['addon']['dashboard'],
			"{official_link}"         => $link['home'],
			"{subject}"               => $subject
		);
		if(MailManager::sendMail($user['email'], $from, "UTF-8", "text/html", $subject, $message, $bindedvalarray)) {
			//put some logging function to monitor
			echo "Mail delivered to ".$user['username']."<br/>";
		}
		else {
			//put some logging function to monitor
			echo "Mail Could not be delivered";
		}

	}
}

exit();