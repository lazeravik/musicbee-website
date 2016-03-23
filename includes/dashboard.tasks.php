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


//enable and disable downloads
	if (isset($_POST['submit'])) {

		if (isset($_POST['type'])
			&& isset($_POST['title'])
			&& isset($_POST['description'])
			&& isset($_POST['mbSupportedVer'])
			&& isset($_POST['dlink'])
			&& isset($_POST['thumb'])
			&& isset($_POST['screenshot_links'])
		) {
			if (addonExists($_POST['title'])) {
				die('{"status": "0", "data": "' . $lang['201'] . '"}');
			} else {
				if (validateInput()) {
					$dashboard = new Dashboard();
					
					//die, if the user alreay submitted more than X numbers of addon that needed aproval!
					//This will prevent the floodgate
					if (count($dashboard->getAllAddonByStatusAndMember($context['user']['id'],0)) > MAX_SUBMIT_WO_APPROVAL) {
						die('{"status": "0", "data": "' . $lang['206'] . '"}');
					}

					$readme = (isset($_POST['readme'])) ? $_POST['readme'] : "";
					//load parsedown markup to html converter
					$Parsedown = new Parsedown();
					$readme_raw = $Parsedown->text($readme);

					//load and use html purifier for the readme notes.
					$readme_html = Format::htmlSafeOutput($readme_raw); //purify the readme note html

					//Phew.... all validations complete, now SUBMIT THE ADDON!
					if ($dashboard->submit($_SESSION['memberinfo']['rank_raw'], $context['user']['id'], $readme_html, "submit")) {
						exit ('{"status": "1", "data": "' . $lang['207'] . '", "callback_function": "submitted"}');
					}
				}
			}
		}
	} elseif (isset($_POST['modify_type'])) {
		if ($_POST['modify_type'] == "delete") {
			$dashboard = new Dashboard();
			if ($dashboard->verifyAuthor($user_info['id'], $_POST['record_id'])) {
				if ($dashboard->deleteAddon($_POST['record_id'])) {
					exit('
					{
						"status": "1", 
						"data": "' . $lang['220'] . '",
						"callback_function": "remove_addon_record"
					}
					');
				} else {
					//:S addon deletation failed! and we have no clue.... bummer
					die('{"status": "0", "data": "' . $lang['221'] . '"}');
				}
			} else {
				//throw error if the author is different than the submitter itself
				die('{"status": "0", "data": "' . $lang['219'] . '"}');
			}
		} elseif ($_POST['modify_type'] == "update") {
			if (validateInput()) {
				$dashboard = new Dashboard();
				//verify if the author can modify it.
				if (!$dashboard->verifyAuthor($user_info['id'], $_POST['record_id'])) {
					die('{"status": "0", "data": "' . $lang['219'] . '"}');
				}

				$readme = (isset($_POST['readme'])) ? $_POST['readme'] : "";
				//load parsedown markup to html converter
				$Parsedown = new Parsedown();
				$readme_raw = $Parsedown->text($readme);
				//load and use html purifier for the readme notes.
				$readme_html = Format::htmlSafeOutput($readme_raw); //purify the readme note html

				//Phew.... all validations complete, now SUBMIT THE ADDON!
				if ($dashboard->submit($_SESSION['memberinfo']['rank_raw'], $context['user']['id'], $readme_html, "update")) {
					echo '{"status": "1", "data": "' . $lang['224'] . '", "callback_function": "submitted"}';
				}
			}


		} else {
			//$_POST['modify_type'] contain unknown title! DIEEEEEE!!!! ^_^
			die('{"status": "0", "data": "' . $lang['222'] . '"}');
		}
	} elseif (isset($_POST['addon_approve'])) {
		if(!$context['user']['can_mod']){
			die('{"status": "0", "data": "' . $lang['dashboard_err_1'] . '"}');
		}
		if ($_POST['addon_approve']==1 || $_POST['addon_approve']==2) {
			$dashboard = new Dashboard();

			if ($dashboard->updateAddonStatus($_POST['addon_id'], $_POST['addon_approve'])) {
				echo '{"status": "1", "data": "' . $lang['224'] . '", "callback_function": "reload_addon_approval_list_overview"}';
				exit();
			}
		} else {
			die('{"status": "0", "data": "' . $lang['222'] . '"}');
		}
	}

	/**
	 * Validation check for dashboard user input
	 *
	 * @return bool
	 */
	function validateInput()
	{
		global $main_menu, $lang, $color_codes;

		if (!array_key_exists($_POST['type'], $main_menu['add-ons']['sub_menu'])) {
			die('{"status": "0", "data": "' . $lang['216'] . '"}');
		}

		if (!Validation::validateMusicBeeVersions(explode(",", $_POST['mbSupportedVer']))) {
			die('{"status": "0", "data": "' . $lang['202'] . '"}');
		}
		if (!Validation::charLimit($_POST['description'], 600)) {
			die('{"status": "0", "data": "' . $lang['203'] . '"}');
		}
		if (!Validation::arrayLimit($_POST['tag'], 10)) {
			die('{"status": "0", "data": "' . $lang['204'] . '"}');
		}
		if (isset($_POST['color'])) {
			if (!array_key_exists($_POST['color'], $color_codes)) {
				die('{"status": "0", "data": "' . $lang['223'] . '"}');
			}
		}
		if (isset($_POST['readme'])) {
			if (!Validation::charLimit($_POST['readme'], 5000))
				die('{"status": "0", "data": "' . $lang['205'] . '"}');
		}

		return true;
	}


	/**
	 * Check if an addon exists with similer title
	 * @param string $title
	 *
	 * @return bool
	 */
	function addonExists($title)
	{
		global $connection, $endMsg, $lang;
		if (databaseConnection()) {
			try {
				$sql = "SELECT * FROM " . SITE_ADDON . " WHERE addon_title = :title";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':title', $title);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				if (count($result) > 0) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {}
		}
	}

