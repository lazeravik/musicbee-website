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


namespace App\Lib\Model;

use App\Lib\Database;
use App\Lib\Help;
use App\Lib\Model;
use App\Lib\Settings;
use App\Lib\Utility\LanguageManager;

class ReleasenoteModel extends Model
{
    public function __construct()
    {
    }

    public function getReleaseNote()
    {
        global $db_info;
        if(Database::isDatabaseConnected())
        {
            try {
                $sql = "SELECT * FROM {$db_info['mb_all']} ORDER BY version DESC";
                $statement = Database::getDatabaseConnection()->prepare($sql);
                $statement->execute();
                $result = array_map('reset',  $statement->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_ASSOC));
                if(count($result) > 0) {
                    return $result;
                }
            } catch(Exception $e) { }

        }

        return null;
    }
}