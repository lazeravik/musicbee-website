<?php
/**
 * Copyright (c) 2017 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 *
 */


use App\Lib\ForumHook;

function setting($settingName = null)
{
    $setting['default-lang']    = 'en_US';
    $setting['charset']         = 'utf-8';
    $setting['ver']             = '1.9.5';
    $setting['show_warning']    = false;
    $setting['is_test'	]	    = true;
    $setting['github_link' ]    = 'https://github.com/Avik-B/musicbee-website';

    if (!empty($settingName)) {
        if (!empty($setting[$settingName])) {
            return $setting[$settingName];
        }
    }
    return $setting;
}


function forumhook()
{
    return ForumHook::getHookContext();
}


function errorCode($errorName = null)
{
    $errorCode = array(
        'ADMIN_ACCESS'      => '101',
        'LOGIN_MUST'        => '102',
        'FORUM_INTEGRATION' => '103',
        'NOT_FOUND'         => '104',
        'NO_DIRECT_ACCESS'  => '105',
        'MOD_ACCESS'        => '106',
    );

    if (!empty($errorName)) {
        if (!empty($errorCode[$errorName])) {
            return $errorCode[$errorName];
        }
    }

    return $errorCode;
}
