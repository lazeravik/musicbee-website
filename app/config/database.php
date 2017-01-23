<?php
$db_info = array();
$db_info['host']              = 'localhost';
$db_info['db_name']           = 'mb_web';
$db_info['db_username']       = 'root';
$db_info['db_password']       = '';
$db_info['db_prefix']         = 'mb_';
$db_info['addon_tbl']         = $db_info['db_prefix'].'addons';
$db_info['member_tbl']        = $db_info['db_prefix'].'member';
$db_info['likes_tbl']         = $db_info['db_prefix'].'likes';
$db_info['download_stat_tbl'] = $db_info['db_prefix'].'download_stat';
$db_info['settings_tbl']      = $db_info['db_prefix'].'settings';
$db_info['mb_all']            = $db_info['db_prefix'].'allversions';
$db_info['mb_current']        = $db_info['db_prefix'].'current_version';
$db_info['help']              = $db_info['db_prefix'].'help';