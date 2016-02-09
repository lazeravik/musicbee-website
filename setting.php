<?php
	/* This file contains all the information of database connection and
	important setting variable */
	
	/*  These are the common variable used for musicbee. 
		DB_HOST, DB_USER, DB_PASS is same for both forum and the site itself
		
		The different one are DATABASE names and TABLE names, since the forum and
		the site won't in the same DB and table..... it is not possible.
		
		By default we will use UTF-8 charset, there is no changing that.
		Also mention DB_HOST port. 
		
		The forum also use prefix(prefix_), which is needed.
		
		FORUM_MEMBER_TBL is member table name for the forum database
	*/
	
	//for my local test mechine setting
	//Host name, default should be localhost:port
	define('DB_HOST', 'localhost');
	
	// //Forum related Setting
	define('FORUM_DB_NAME', 'musicbeeDemo'); //Forum Database name
	define('FORUM_DB_USER', 'root'); //database username
	define('FORUM_DB_PASS', ''); //database password
	define('FORUM_DB_PREFIX', 'mb_'); //database prefix
	define('FORUM_MEMBER_TBL', FORUM_DB_PREFIX.'members'); //forum member table name
	
	//Website related Setting
	define('SITE_DB_NAME', 'getmu0_MusicBee'); //Website Database Name
	define('SITE_DB_USER', 'root'); //username
	define('SITE_DB_PASS', ''); //password
	define('SITE_DB_PREFIX', ''); //database prefix


	
	//For test.getmusicbee.com site setting
	//Host name, default should be localhost:port

	// //Forum related Setting
	// define('FORUM_DB_NAME', 'getmu0_smftest'); //Forum Database name
	// define('FORUM_DB_USER', 'getmu0_smftest'); //database username
	// define('FORUM_DB_PASS', 'MbSmfTest1'); //database password
	// define('FORUM_DB_PREFIX', 'smftest_'); //database prefix
	// define('FORUM_MEMBER_TBL', FORUM_DB_PREFIX.'members'); //forum member table name
	
	// //Website related Setting
	// define('SITE_DB_NAME', 'getmu0_MusicBee'); //Website Database Name
	// define('SITE_DB_USER', 'getmu0_AvikB'); //username
	// define('SITE_DB_PASS', '[mbAvikB01]'); //password
	// define('SITE_DB_PREFIX', ''); //database prefix
	
	/*tables*/
	define('SITE_MEMBER_TBL', SITE_DB_PREFIX.'member'); //Website member table name
	define('SITE_MB_CURRENT_VERSION_TBL', SITE_DB_PREFIX.'current_version'); //Musicbee version table name
	define('SITE_MB_ALL_VERSION_TBL', SITE_DB_PREFIX.'allversions'); //Musicbee version table name
	define('SITE_ADDON', SITE_DB_PREFIX.'addons');
	define('SITE_ADDON_LIKE', SITE_DB_PREFIX.'likes');

	/*addon center*/
	define('MAX_SUBMIT_WO_APPROVAL', '10');


	/*Client IDs and Secrets*/
	//Imgur setting
	define('IMGUR_CLIENT_ID', '7f0764588452050');
	define('IMGUR_CLIENT_SECRET', '0061179a2f436175dc84e83deabc23aa12ef255a');

	//Mediafire setting
	define('MF_EMAIL', 'avik.avikbiswas@gmail.com');
	define('MF_PASS', 'bwLJvprnT4iH7erexAdu');
	define('MF_API_KEY', 'qbircy2842yu1ybz0m89wx2yu9iadg7hucb11z55');
	define('MF_APP_ID', '50098');

?>