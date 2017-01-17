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

use App\Lib\Utility\Enum;

class MBReleaseManager
{
    protected static $dbcon;

    /**
     * Get Musicbee release by type such STABLE, BETA, PATCH
     * @param $releaseType  (MBReleaseType enum)
     * @param $dbcon        (Database connection)
     * @return MBRelease|\Exception
     * @throws \Exception
     */
    public static function getMusicBeeRelease($releaseType, $dbcon)
    {
        if ($dbcon === null) {
            throw new \Exception("No Connection to database!", 000);
        }

        self::$dbcon = $dbcon;

        switch ($releaseType) {
            case MBReleaseType::STABLE:
                return self::getFormattedRelease(self::getStableRelease());
                break;
            case MBReleaseType::BETA:
                return self::getFormattedRelease(self::getBetaRelease());
                break;
            case MBReleaseType::PATCH:
                return self::getFormattedRelease(self::getPatchRelease());
                break;
            default:
                return new \Exception("Wrong release type", 100);
                break;
        }

    }

    /**
     * get musicbee release data in a nice MBRelease class container
     * @param $releasedata
     * @return MBRelease|\Exception
     */
    private static function getFormattedRelease($releasedata)
    {
        if (!(count($releasedata) > 0)) {
            return new \Exception("Could not find any release!", 102);
        }

        $releasedata = $releasedata[0];

        $download = [
            "available" => $releasedata['available'],
            "installer" => [
                $releasedata['DownloadLink'],
                $releasedata['MirrorLink1'],
                $releasedata['MirrorLink2'],
            ],
            "portable" => [
                $releasedata['PortableLink'],
            ],
        ];

        $misc = [
            "is_beta"                   => (bool)$releasedata['beta'],
            "is_available_in_dashboard" => (bool)$releasedata['dashboard_availablity'],
            "is_major"                  => (bool)$releasedata['major'],
            "release_note"              => $releasedata['release_note'],
            "release_note_html"         => $releasedata['release_note_html'],
        ];


        return new MBRelease(
            $releasedata['version'],
            $releasedata['appname'],
            $releasedata['release_date'],
            $download,
            $releasedata['supported_os'],
            $releasedata['message'],
            $misc
        );
    }

    /**
     * Get Musicbee stable release data from the database
     * @return mixed
     * @throws \Exception
     */
    private static function getStableRelease()
    {
        global $db_info;

        try {
            $sql =<<<SQL
  SELECT * FROM {$db_info['mb_all']} 
  LEFT JOIN {$db_info['mb_current']} 
       ON {$db_info['mb_all']}.version = {$db_info['mb_current']}.version
  WHERE ID_VERSION = 0
SQL;
            $statement = self::$dbcon->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Can't get msuicbee release data from database!");
        }
    }

    /**
     * Get Musicbee beta release data from the database
     * @return mixed
     * @throws \Exception
     */
    private static function getBetaRelease()
    {
        global $db_info;

        try {
            $sql =<<<SQL
  SELECT * FROM {$db_info['mb_current']} 
  LEFT JOIN {$db_info['mb_all']} 
    ON {$db_info['mb_all']}.version = {$db_info['mb_current']}.version
  WHERE ID_VERSION = 1
SQL;
            $statement = self::$dbcon->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Can't get msuicbee release data from database!");
        }
    }

    /**
     * Get Musicbee patch release data from the database
     * @return mixed
     * @throws \Exception
     */
    private static function getPatchRelease()
    {
        global $db_info;

        try {
            $sql =<<<SQL
  SELECT * FROM {$db_info['mb_current']} 
  WHERE ID_VERSION = 2
SQL;
            $statement = self::$dbcon->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Can't get msuicbee release data from database!");
        }
    }
}
