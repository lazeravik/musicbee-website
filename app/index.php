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

require_once '../vendor/autoload.php';

/**
 * load i18n gettext module and language list
 */
require_once 'locale/lang.list.php';
require_once 'libraries/gettext/gettext.inc.php';

/**
 * Load website and database configuration
 */
require_once 'config/config.php';
require_once 'config/paths.php';
require_once 'config/database.php';

use App\Lib\Bootstrap;
use App\Lib\Utility\Route;
use App\Lib\Utility\Router;
use App\Lib\Utility\LanguageManager;
use App\Lib\Utility\Config as cfg;
use App\Lib\Utility\Session;
use App\Lib\ForumHook;


/**
 * Url routing initialize
 */
$router = new Router();
/**
 * Setup localization, cookies and redirect to proper localized url!
 */
LanguageManager::init($router->getLanguageParamFromUrl(), $langList);
LanguageManager::redirectToUrlWithLanuageCode($router);

/**
 * Load in menu config after localization is done.
 * Load everything else that contans __() after localization
 */
require_once 'config/menuconfig.php';

/**
 * It is time to init bootstrap. This will load the MVC framework
 */
$app = new Bootstrap($router);
