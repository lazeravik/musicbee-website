<?php
#######################################################################
#### This file contains all the information of database connection ####
#### ------------------------------------------------------------- ####
#### Website related Setting									   ####
#### MODIFY THE FOLLOWING WITH THE CORRECT DATABASE & AUTH DETAILS ####
#### ------------------------------------------------------------- ####
#### user: getmu0_AvikB, pass: [mbAvikB01], db: getmu0_MusicBee    ####
#######################################################################
define('DB_HOST', 'localhost');
define('SITE_DB_NAME', 'mb_web');
define('SITE_DB_USER', 'root');
define('SITE_DB_PASS', '');
define('SITE_DB_PREFIX', 'mb_');

#######################################################################
#### Website Tables												   ####
#### ------------------------------------------------------------- ####
#### DO NOT MODIFY ANYTHING!									   ####
#######################################################################
define('SITE_MEMBER_TBL', SITE_DB_PREFIX.'member'); //Website member table name
define('SITE_MB_CURRENT_VERSION_TBL', SITE_DB_PREFIX.'current_version'); //Musicbee version table name
define('SITE_MB_ALL_VERSION_TBL', SITE_DB_PREFIX.'allversions'); //Musicbee version table name
define('SITE_ADDON', SITE_DB_PREFIX.'addons');
define('SITE_ADDON_LIKE', SITE_DB_PREFIX.'likes');
define('SITE_DOWNLOAD_STAT', SITE_DB_PREFIX.'download_stat');
define('SETTINGS', SITE_DB_PREFIX.'settings');
define('HELP', SITE_DB_PREFIX.'help');

#######################################################################
#### we already defined the table, but we can not use them in 	   ####
#### SQL query with brackets.									   ####
#### ------------------------------------------------------------- ####
#### eg.     SELECT * FROM {TABLE_NAME}							   ####
#### This won't work, so we are gonna create an array of tables,   ####
#### with this we can easily use them in query					   ####
#### ------------------------------------------------------------- ####
#### DO NOT MODIFY ANYTHING!									   ####
#######################################################################
$db_info = array();
$db_info['addon_tbl'] = SITE_ADDON;
$db_info['member_tbl'] = SITE_MEMBER_TBL;
$db_info['likes_tbl'] = SITE_ADDON_LIKE;
$db_info['download_stat_tbl'] = SITE_DOWNLOAD_STAT;
$db_info['settings_tbl'] = SETTINGS;
$db_info['mb_all'] = SITE_MB_ALL_VERSION_TBL;
$db_info['mb_current'] = SITE_MB_CURRENT_VERSION_TBL;
$db_info['help'] = HELP;
