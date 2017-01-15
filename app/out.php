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
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/functions.php';
require_once $link['root'] . 'classes/Stats.php';

//Creates an array from the URI without using lowercase
$params_raw = explode("/", $_SERVER['REQUEST_URI']);

parse_str(str_replace("?", "", $params_raw[2]),$url_params);


if (isset($url_params['type']) && isset($url_params['id']) && isset($url_params['r'])) {
	if($url_params['type'] == "addon") {
		$stat['ip'] = $_SERVER['REMOTE_ADDR'];
		$stat['is_registered'] = ($mb['user']['is_guest']) ? "0" : "1";

		$stat['stat_type'] = $url_params['type'];
		$stat['id'] = $url_params['id'];

		$Stats = new Stats();
		$Stats->addStat($stat);

		$url = $url_params['r'];
		include $link['root'].'views/redirect.template.php';
	}
}
/**
 * For setting language file all we need is to set  `$_GET['lang']`,
 * all other things are taken care of in functions.php file
 */
elseif($url_params['type'] = "lang" || isset($_GET['lang'])){

	//Now that we have `$_GET['lang']` set, head back to wherever we were
	header('Location: '.$_SESSION['previous_page']);
}
else {
	include $link['root'] . 'views/redirect.template.php';
}
