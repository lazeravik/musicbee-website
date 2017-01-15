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

/**
 * @author : AvikB
 * @version: 1.0
 *
 */
$no_guests = true; //kick off the guests
$no_directaccess = true;

require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';
require_once $link['root'].'classes/Dashboard.php';
require_once $link['root'].'classes/Help.php';
include_once $link['root'].'includes/parsedown/Parsedown.php';
include_once $link['root'].'includes/parsedown/ParsedownExtra.php';

//Addon submission check
if(isset($_POST['submit'])) {

	//If Add-on submission is turned of show error
	if(!$setting['addonSubmissionOn']) {
		die('{"status": "0", "data": "'.$lang['dashboard_err_20'].'"}');
	}

	//If user already reached maximum submission limit/day show error
	if(!canUserSubmitAnymoreToday()) {
		die('{"status": "0", "data": "'.$lang['dashboard_err_22'].'"}');
	}


	//if all user input is ok then move on
	if(validateInput()) {
		$dashboard = new Dashboard();
		//check if an addon with similer name exists or not
		if(!$dashboard->addonExists($_POST['title'], null)) {

			//die, if the user alreay submitted more than X numbers of addon that needed aproval!
			//This will prevent the floodgate
			if($dashboard->getAllAddonCountByStatusAndMember($mb['user']['id'], 0) > $setting['maxSubmitWithOutApproval']) {
				die('{"status": "0", "data": "'.$lang['dashboard_err_10'].'"}');
			}

			$readme = (isset($_POST['readme'])) ? $_POST['readme'] : "";
			//load parsedown for markdown to html converter
			$ParsedownExtra = new ParsedownExtra();
			$ParsedownExtra->setBreaksEnabled(true);
			$readme_raw = $ParsedownExtra->text($readme);

			//load and use html purifier for the readme notes.
			$readme_html = Format::htmlSafeOutput($readme_raw); //purify the readme note html

			//Phew.... all validations complete, now SUBMIT THE ADDON!
			if($dashboard->submit($readme_html, "submit")) {
				//@todo: Add an item to mail queue, and send it to admin
				exit ('{"status": "1", "data": "'.$lang['dashboard_msg_11'].'", "callback_function": "submitted"}');
			}
		} else {
			die('{"status": "0", "data": "'.$lang['dashboard_err_5'].'"}');
		}
	}
}
//Addon modification after it is submitted
elseif(isset($_POST['modify_type'])) {
	$dashboard = new Dashboard();

	//permanent delete will delete the addon forever!
	if($_POST['modify_type'] == "permanent_delete") {

		//Only an admin can permanently delete an addon
		if($mb['user']['is_admin']) {
			if($dashboard->deleteAddon($_POST['record_id'])) {
				exit('
				{
					"status": "1",
					"data": "'.$lang['dashboard_msg_7'].'",
					"callback_function": "remove_addon_record"
				}
				');
			} else {
				//:S addon deletation failed! and we have no clue.... bummer
				die('{"status": "0", "data": "'.$lang['dashboard_err_14'].'"}');
			}
		} else {
			//throw error if the author is different than the submitter itself
			die('{"status": "0", "data": "'.$lang['dashboard_err_12'].'"}');
		}
	}

	//Soft delete won't delete it, but put it in to be deleted list, it will be deleted whenever the delete script executes!
	else if($_POST['modify_type'] == "soft_delete") {

		//You can not soft delete an addon that is already soft deleted
		if($dashboard->getAddonStatus($_POST['record_id']) == "3") {
			die('{"status": "0", "data": "'.$lang['dashboard_msg_9'].'"}');
		}

		//Mod/Admin/addon author will be able to soft delete and addon
		if($dashboard->verifyAuthor($mb['user']['id'], $_POST['record_id']) || $mb['user']['can_mod']) {
			if($dashboard->updateAddonStatus($_POST['record_id'], "3", $mb['user']['id'])) {
				//@todo: now that the item is deleted, check if it is added to mail queue, if true remove it.
				exit('
				{
					"status": "1",
					"data": "'.$lang['dashboard_msg_8'].'",
					"callback_function": "remove_addon_record"
				}
				');
			} else {
				//:S addon deletation failed! and we have no clue.... bummer
				die('{"status": "0", "data": "'.$lang['dashboard_err_14'].'"}');
			}
		} else {
			//throw error if the author is different than the submitter itself
			die('{"status": "0", "data": "'.$lang['dashboard_err_12'].'"}');
		}
	}

	//Update addon conetnts
	else if($_POST['modify_type'] == "update") {
		if(validateInput()) {

			//If the addon is already soft deleted and the user is not an admin or mod die!!
			if($dashboard->getAddonStatus($_POST['record_id']) == "3" && !$mb['user']['can_mod']) {
				die('{"status": "0", "data": "'.$lang['dashboard_msg_9'].'"}');
			}

			//verify if the author can modify it.
			if(!$dashboard->verifyAuthor($user_info['id'], $_POST['record_id']) && !$mb['user']['can_mod']) {
				die('{"status": "0", "data": "'.$lang['dashboard_err_12'].'"}');
			}

			//check if an addon with similer name except for this one exists or not
			//We need to escape special chars since we submitted the title with escaped value, otherwise they won't match
			if(!$dashboard->addonExists(htmlspecialchars(trim($_POST['title']), ENT_QUOTES, "UTF-8"), $_POST['record_id'])) {

				$readme = (isset($_POST['readme'])) ? $_POST['readme'] : "";
				//load parsedown markup to html converter
				$ParsedownExtra = new ParsedownExtra();
				$ParsedownExtra->setBreaksEnabled(true);
				$readme_raw = $ParsedownExtra->text($readme);

				//load and use html purifier for the readme notes.
				$readme_html = Format::htmlSafeOutput($readme_raw); //purify the readme note html
				//Phew.... all validations complete, now SUBMIT THE ADDON!
				if($dashboard->submit($readme_html, "update")) {
					exit('{"status": "1", "data": "'.$lang['dashboard_msg_12'].'", "callback_function": "submitted", "origin": "dashboard.task line 99"}');
				}
			} else {
				die('{"status": "0", "data": "'.$lang['dashboard_err_5'].'"}');
			}
		}
	} else {
		//$_POST['modify_type'] contain unknown title! DIEEEEEE!!!! ^_^
		die('{"status": "0", "data": "'.$lang['dashboard_err_15'].'"}');
	}
}

//Update addon status such as approve
elseif(isset($_POST['addon_approve'])) {
	if(!$mb['user']['can_mod']) {
		die('{"status": "0", "data": "'.$lang['dashboard_err_1'].'"}');
	}
	if($_POST['addon_approve'] == 1 || $_POST['addon_approve'] == 2) {
		$dashboard = new Dashboard();

		if($dashboard->updateAddonStatus($_POST['addon_id'], $_POST['addon_approve'], $mb['user']['id'])) {
			exit('{"status": "1", "data": "'.$lang['dashboard_msg_12'].'", "callback_function": "reload_addon_approval_list_overview"}');
		}
	} else {
		die('{"status": "0", "data": "'.$lang['dashboard_err_15'].'"}');
	}
}

//Transfer addon ownership to other users
elseif(isset($_POST['addon_transfer'])) {
	if(!$mb['user']['can_mod']) {
		die('{"status": "0", "data": "'.$lang['dashboard_err_1'].'"}');
	}

	if(!(isset($_POST['addon_id']) && isset($_POST['user_id']))){
		die('{"status": "0", "data": "'.$lang['transfer_err_invalid_data'].'"}');
	}

	if(empty($_POST['addon_id']) || empty($_POST['user_id'])){
		die('{"status": "0", "data": "'.$lang['transfer_err_invalid_data'].'"}');
	}

	if(Member::memberInfo($_POST['user_id']) == null){
		die('{"status": "0", "data": "'.$lang['transfer_err_user_notexists'].'"}');
	}

	$dashboard = new Dashboard();
	if($dashboard->transferAddonRights($_POST['user_id'], $_POST['addon_id'])) {
		//@todo: send an email to both perticipant to let them know
		exit('{"status": "1", "data": "'.$lang['transfer_success_done'].'", "callback_function": "transfer_success"}');
	} else {
		die('{"status": "0", "data": "'.$lang['transfer_err_unknown'].'"}');
	}

} elseif(isset($_POST['site_setting'])) {
	if($_POST['site_setting'] == "true") {

		//Make sure the user is an admin or DIE!!
		if(!$mb['user']['is_admin']) {
			die('{"status": "0", "data": "'.$lang['dashboard_err_1'].'"}');
		}

		if(isset($_POST['setting_type'])){
			if($_POST['setting_type'] == 'press'){

				$press = (isset($_POST['press_content'])) ? $_POST['press_content'] : "";
				//load parsedown markup to html converter
				$ParsedownExtra = new ParsedownExtra();
				$ParsedownExtra->setBreaksEnabled(true);
				$press_raw = $ParsedownExtra->text($press);

				//load and use html purifier for the readme notes.
				$press_html = Format::htmlSafeOutput($press_raw); //purify the readme note html

				$_POST['press_md'] = $press;
				$_POST['press_html'] = $press_html;

				$help = new Help();
				if($help->saveEdit()) {
					exit('{"status": "1", "data": "'.$lang['dashboard_msg_10'].'", "callback_function": "setting_saved"}');
				} else {
					die('{"status": "0", "data": "'.$lang['dashboard_err_19'].'"}');
				}
			} elseif ($_POST['setting_type'] == 'api'){

				$api = (isset($_POST['api'])) ? $_POST['api'] : "";
				//load parsedown markup to html converter
				$ParsedownExtra = new ParsedownExtra();
				$ParsedownExtra->setBreaksEnabled(true);
				$api_raw = $ParsedownExtra->text($api);

				//load and use html purifier for the readme notes.
				$api_html = Format::htmlSafeOutput($api_raw); //purify the readme note html

				$_POST['api_md'] = $api;
				$_POST['api_html'] = $api_html;

				$help = new Help();
				if($help->saveEdit()) {
					exit('{"status": "1", "data": "'.$lang['dashboard_msg_10'].'", "callback_function": "setting_saved"}');
				} else {
					die('{"status": "0", "data": "'.$lang['dashboard_err_19'].'"}');
				}
			} elseif ($_POST['setting_type'] == 'help') {

				$faq = (isset($_POST['faq'])) ? $_POST['faq'] : "";
				//load parsedown markup to html converter
				$ParsedownExtra = new ParsedownExtra();
				$ParsedownExtra->setBreaksEnabled(true);
				$faq_raw = $ParsedownExtra->text($faq);

				//load and use html purifier for the readme notes.
				$faq_html = Format::htmlSafeOutput($faq_raw); //purify the readme note html

				$_POST['faq_md'] = $faq;
				$_POST['faq_html'] = $faq_html;

				$help = new Help();
				if($help->saveEdit()) {
					exit('{"status": "1", "data": "'.$lang['dashboard_msg_10'].'", "callback_function": "setting_saved"}');
				} else {
					die('{"status": "0", "data": "'.$lang['dashboard_err_19'].'"}');
				}
			} else {
				$dashboard = new Dashboard();
				if($dashboard->saveSiteSetting()) {
					exit('{"status": "1", "data": "'.$lang['dashboard_msg_10'].'", "callback_function": "setting_saved"}');
				} else {
					die('{"status": "0", "data": "'.$lang['dashboard_err_19'].'"}');
				}
			}
		} else {
			die('{"status": "0", "data": "'.$lang['dashboard_err_19'].'"}');
		}


	}
}

/**
 * Validation check for dashboard user input
 *
 * @return bool
 */
function validateInput() {
	global $mb, $lang;

	if(isset($_POST['type']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['mbSupportedVer']) && isset($_POST['dlink']) && isset($_POST['thumb']) && isset($_POST['screenshot_links']) && isset($_POST['readme']) && isset($_POST['beta'])) {
		//check if the addon is beta then a support forum link must be provided else show error
		if($_POST['beta'] == "1" && empty($_POST['support'])) {
			die('{"status": "0", "data": "'.$lang['dashboard_err_16'].'"}');
		}

		if(!isset($mb['main_menu']['add-ons']['sub_menu'][$_POST['type']])) {
			die('{"status": "0", "data": "'.$lang['dashboard_err_4'].'"}');
		}

//		if(!Validation::validateMusicBeeVersions(explode(",", $_POST['mbSupportedVer']))) {
//			die('{"status": "0", "data": "'.$lang['dashboard_err_6'].'"}');
//		}

		if(!Validation::validateMusicBeeVersion($_POST['mbSupportedVer'])) {
			die('{"status": "0", "data": "'.$lang['dashboard_err_6'].'"}');
		}

		if(!Validation::charLimit($_POST['description'], 600)) {
			die('{"status": "0", "data": "'.$lang['dashboard_err_7'].'"}');
		}

		if(!Validation::arrayLimit($_POST['tag'], 10)) {
			die('{"status": "0", "data": "'.$lang['dashboard_err_8'].$_POST['tag'].'"}');
		}

		if(!Validation::charLimit($_POST['readme'], 15000)) {
			die('{"status": "0", "data": "'.$lang['dashboard_err_9'].'"}');
		}
	} else {
		die('{"status": "0", "data": "'.$lang['dashboard_err_15'].'"}');
	}

	return true;
}


/**
 * Check if user has reached submission limit per day
 *
 * @return bool
 */
function canUserSubmitAnymoreToday() {
	global $connection, $db_info, $mb, $setting;

	$currentdate = date("F j, Y");

	if(databaseConnection()) {
		try {
			$sql = "SELECT * FROM {$db_info['addon_tbl']} WHERE publish_date = :pub_date AND ID_AUTHOR = {$mb['user']['id']}";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':pub_date', $currentdate);
			$statement->execute();
			$result = count($statement->fetchAll(PDO::FETCH_ASSOC));

			if($result <= $setting['maximumAddonSubmissionPerDay']){
				return true;
			} else {
				return false;
			}

		} catch(Exception $e) {

		}
	}
}
