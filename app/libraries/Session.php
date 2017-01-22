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


namespace App\Lib\Utility;


class Session
{
    /**
     * Start a new session if no session detected.....
     * WARNING! IT REQUIRES PHP 5.4 OR LATER
     */
    public static function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session variable
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }


    /**
     * Get a vlaue of session variable
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * Destroy the session
     */
    public static function destroy()
    {
        session_destroy();
    }
}