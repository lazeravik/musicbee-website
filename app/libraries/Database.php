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

namespace App\Lib;

class Database
{
    private static $dbcon;

    public static function isDatabaseConnected()
    {
        global $db_info;

        if (self::$dbcon != null) {
            return true;
        } else {
            try {
                self::$dbcon = new \PDO(
                    'mysql:host='.$db_info['host'].';
                    dbname='.$db_info['db_name'].';
                    charset=utf8',
                    $db_info['db_username'],
                    $db_info['db_password']
                );
                self::$dbcon->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$dbcon->exec('set session sql_mode = traditional');
                self::$dbcon->exec('set session innodb_strict_mode = on');

                return true;
            } catch (\PDOException $e) {
                throw new \Exception("Can not connect to database!", $e->getCode());
            }
        }
    }

    public static function getDatabaseConnection()
    {
        return self::$dbcon;
    }
}
