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
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $siteRoot . 'classes/Stats.php';

parse_str(str_replace("?", "", $params[2]),$url_params); 


if (!(isset($url_params['type']) && isset($url_params['r']))) {
	invalidRedirect();
	exit();
}

$stat['ip'] = $_SERVER['REMOTE_ADDR'];
$stat['is_registered'] = ($context['user']['is_guest'])? "0" : "1";

if ($url_params['type'] == "addon" && isset($url_params['id'])) {

	$stat['stat_type'] = $url_params['type'];
	$stat['id'] = $url_params['id'];

	$Stats = new Stats();
	$Stats->addStat($stat);

	$url = $url_params['r'];
	include $siteRoot . 'includes/redirect.template.php';
} else {
	include $siteRoot . 'includes/redirect.template.php';
}



