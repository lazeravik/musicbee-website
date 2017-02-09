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

class MBReleaseManager extends Database
{
    /**
     * Get Musicbee release by type such STABLE, BETA, PATCH
     * @param $releaseType (MBReleaseType enum)
     * @return MBRelease|\Exception
     * @throws \Exception
     * @internal param $dbcon (Database connection)
     */
    public static function getMusicBeeRelease($releaseType)
    {
        if (!self::isDatabaseConnected()) {
            if (self::getDatabaseConnection() === null) {
                throw new \Exception("No Connection to database!", 000);
            }
        }

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
                return new \Exception("Wrong data type", 100);
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
            "is_beta"                   => (bool)(isset($releasedata['beta'])?:null),
            "is_available_in_dashboard" => (bool)(isset($releasedata['dashboard_availablity'])?:null),
            "is_major"                  => (bool)(isset($releasedata['major'])?:null),
            "release_note"              => (isset($releasedata['release_note'])? $releasedata['release_note'] :null),
            "release_note_html"         => (isset($releasedata['release_note_html'])? $releasedata['release_note_html'] :null),
        ];


        return new MBRelease(
            (isset($releasedata['version'])?$releasedata['version']:null),
            (isset($releasedata['appname'])?$releasedata['appname']:null),
            (isset($releasedata['release_date'])?$releasedata['release_date']:null),
            $download,
            (isset($releasedata['supported_os'])?$releasedata['supported_os']:null),
            (isset($releasedata['message'])?$releasedata['message']:null),
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
            $statement = self::getDatabaseConnection()->prepare($sql);
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
            $statement = self::getDatabaseConnection()->prepare($sql);
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
            $statement = self::getDatabaseConnection()->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Can't get msuicbee release data from database!");
        }
    }
}
