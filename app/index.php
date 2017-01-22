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

include_once '../forum/SSI.php';
include_once '../vendor/autoload.php';

/**
 * load i18n gettext module and language list
 */
include_once 'locale/lang.list.php';
require_once 'libraries/gettext/gettext.inc.php';

/**
 * Load website and database configuration
 */
include_once 'config/paths.php';
include_once 'config/dbconfig.php';
include_once 'config/menuconfig.php';
include_once 'config/settings.php';

use App\Lib\Bootstrap;
use App\Lib\Utility\Route;
use App\Lib\Utility\Router;
use App\Lib\Utility\LanguageManager;
use App\Lib\Utility\Config as cfg;
use App\Lib\Utility\Session;

$langManager = new LanguageManager();
$app = new Bootstrap($langManager);
