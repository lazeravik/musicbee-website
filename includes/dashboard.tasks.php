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
	 * @author : AvikB
	 * @version: 1.0
	 *
	 */
	$no_guests = true; //kick off the guests
	require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

	require_once $siteRoot . 'classes/Dashboard.php';
	include_once $siteRoot . 'includes/parsedown/Parsedown.php';

if (isset($_POST['submit'])) {
	//if all user input is ok then move on
	if (validateInput ()) {
		$dashboard = new Dashboard();
		//check if an addon with similer name exists or not
		if (!$dashboard->addonExists ($_POST['title'], null)) {

			//die, if the user alreay submitted more than X numbers of addon that needed aproval!
			//This will prevent the floodgate
			if (count ($dashboard->getAllAddonByStatusAndMember ($context['user']['id'], 0)) > MAX_SUBMIT_WO_APPROVAL) {
				die('{"status": "0", "data": "' . $lang['dashboard_err_10'] . '"}');
			}

			$readme = (isset($_POST['readme'])) ? $_POST['readme'] : "";
			//load parsedown markup to html converter
			$Parsedown = new Parsedown();
			$readme_raw = $Parsedown->text ($readme);

			//load and use html purifier for the readme notes.
			$readme_html = Format::htmlSafeOutput ($readme_raw); //purify the readme note html

			//Phew.... all validations complete, now SUBMIT THE ADDON!
			if ($dashboard->submit ($_SESSION['memberinfo']['rank_raw'], $context['user']['id'], $readme_html, "submit")) {
				exit ('{"status": "1", "data": "' . $lang['dashboard_err_11'] . '", "callback_function": "submitted"}');
			}
		} else {
			die('{"status": "0", "data": "' . $lang['dashboard_err_5'] . '"}');
		}
	}
} elseif (isset($_POST['modify_type'])) {
	$dashboard = new Dashboard();

	//permanent delete will delete the addon forever!
	if ($_POST['modify_type'] == "permanent_delete") {

		//Only an admin can permanently delete an addon
		if ($context['user']['is_admin']) {
			if ($dashboard->deleteAddon($_POST['record_id'])) {
				exit('
				{
					"status": "1",
					"data": "' . $lang['dashboard_msg_7'] . '",
					"callback_function": "remove_addon_record"
				}
				');
			} else {
				//:S addon deletation failed! and we have no clue.... bummer
				die('{"status": "0", "data": "' . $lang['dashboard_err_14'] . '"}');
			}
		} else {
			//throw error if the author is different than the submitter itself
			die('{"status": "0", "data": "' . $lang['dashboard_err_12'] . '"}');
		}
	}

	//Soft delete won't delete it, but put it in to be deleted list, it will be deleted whenever the delete script executes!
	if ($_POST['modify_type'] == "soft_delete") {

		//You can not soft delete an addon that is already soft deleted
		if($dashboard->getAddonStatus($_POST['record_id'])['status'] == "3"){
			die('{"status": "0", "data": "'.$lang['dashboard_msg_9'].'"}');
		}

		//Mod/Admin/addon author will be able to soft delete and addon
		if ($dashboard->verifyAuthor($context['user']['id'], $_POST['record_id']) || $context['user']['can_mod']) {
			if ($dashboard->updateAddonStatus($_POST['record_id'],"3",$context['user']['id'])) {
				exit('
				{
					"status": "1",
					"data": "' . $lang['dashboard_msg_8'] . '",
					"callback_function": "remove_addon_record"
				}
				');
			} else {
				//:S addon deletation failed! and we have no clue.... bummer
				die('{"status": "0", "data": "' . $lang['dashboard_err_14'] . '"}');
			}
		} else {
			//throw error if the author is different than the submitter itself
			die('{"status": "0", "data": "' . $lang['dashboard_err_12'] . '"}');
		}
	} elseif ($_POST['modify_type'] == "update") {
		if (validateInput()) {

			if($dashboard->getAddonStatus($_POST['record_id'])['status'] == "3"){
				die('{"status": "0", "data": "'.$lang['dashboard_msg_9'].'"}');
			}

			//check if an addon with similer name except for this one exists or not
			if(!$dashboard->addonExists($_POST['title'], $_POST['record_id'])) {
				//verify if the author can modify it.
				if (!$dashboard->verifyAuthor ($user_info['id'], $_POST['record_id'])) {
					die('{"status": "0", "data": "' . $lang['dashboard_err_12'] . '"}');
				}
				$readme = (isset($_POST['readme'])) ? $_POST['readme'] : "";
				//load parsedown markup to html converter
				$Parsedown = new Parsedown();
				$readme_raw = $Parsedown->text ($readme);
				//load and use html purifier for the readme notes.
				$readme_html = Format::htmlSafeOutput ($readme_raw); //purify the readme note html
				//Phew.... all validations complete, now SUBMIT THE ADDON!
				if ($dashboard->submit ($_SESSION['memberinfo']['rank_raw'], $context['user']['id'], $readme_html, "update")) {
					echo '{"status": "1", "data": "' . $lang['dashboard_err_17'] . '", "callback_function": "submitted", "origin": "dashboard.task line 99"}';
				}
			} else {
				die('{"status": "0", "data": "' . $lang['dashboard_err_5'] . '"}');
			}
		}
	} else {
		//$_POST['modify_type'] contain unknown title! DIEEEEEE!!!! ^_^
		die('{"status": "0", "data": "' . $lang['dashboard_err_15'] . '"}');
	}
} elseif (isset($_POST['addon_approve'])) {
	if(!$context['user']['can_mod']){
		die('{"status": "0", "data": "' . $lang['dashboard_err_1'] . '"}');
	}
	if ($_POST['addon_approve']==1 || $_POST['addon_approve']==2) {
		$dashboard = new Dashboard();

		if ($dashboard->updateAddonStatus($_POST['addon_id'], $_POST['addon_approve'], $context['user']['id'])) {
			echo '{"status": "1", "data": "' . $lang['dashboard_err_17'] . '", "callback_function": "reload_addon_approval_list_overview"}';
			exit();
		}
	} else {
		die('{"status": "0", "data": "' . $lang['dashboard_err_15'] . '"}');
	}
} elseif(isset($_POST['action'])) {
	if ($_POST['action']=="search") {

	}
}

	/**
	 * Validation check for dashboard user input
	 *
	 * @return bool
	 */
	function validateInput()
	{
		global $main_menu, $lang;

		if (isset($_POST['type'])
		 && isset($_POST['title'])
		 && isset($_POST['description'])
		 && isset($_POST['mbSupportedVer'])
		 && isset($_POST['dlink'])
		 && isset($_POST['thumb'])
		 && isset($_POST['screenshot_links'])
		 && isset($_POST['readme'])
		 && isset($_POST['beta'])
		) {
			//check if the addon is beta then a support forum link must be provided else show error
			if ($_POST['beta'] == "1" && empty($_POST['support'])) {
				die('{"status": "0", "data": "' . $lang['dashboard_err_16'] . '"}');
			}

			if (!isset($main_menu['add-ons']['sub_menu'][$_POST['type']])) {
				die('{"status": "0", "data": "' . $lang['dashboard_err_4'] . '"}');
			}

			if (!Validation::validateMusicBeeVersions (explode (",", $_POST['mbSupportedVer']))) {
				die('{"status": "0", "data": "' . $lang['dashboard_err_6'] . '"}');
			}

			if (!Validation::charLimit ($_POST['description'], 600)) {
				die('{"status": "0", "data": "' . $lang['dashboard_err_7'] . '"}');
			}

			if (!Validation::arrayLimit ($_POST['tag'], 10)) {
				die('{"status": "0", "data": "' . $lang['dashboard_err_8'] . $_POST['tag'] . '"}');
			}

			if (!Validation::charLimit ($_POST['readme'], 5000)) {
				die('{"status": "0", "data": "' . $lang['dashboard_err_9'] . '"}');
			}
		} else {
			die('{"status": "0", "data": "' . $lang['dashboard_err_15'] . '"}');
		}

		return true;
	}




