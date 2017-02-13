<?php
/**
 * Created by PhpStorm.
 * User: AvikB
 * Date: 13-02-2017
 * Time: 08:17 PM
 */

namespace App\Lib;


class Settings extends Database
{
    public static function getLinks($link_key = null)
    {
        if (!self::isDatabaseConnected()) {
            if (self::getDatabaseConnection() === null) {
                throw new \Exception("No Connection to database!", 000);
            }
        }

        $setting = self::getSetting();
        $link = [
            "twitterLink"       => isset($setting['twitterLink'])       ?   $setting['twitterLink']         : null,
            "wikiaLink"         => isset($setting['wikiaLink'])         ?   $setting['wikiaLink']           : null,
            "wishlistLink"      => isset($setting['wishlistLink'])      ?   $setting['wishlistLink']        : null,
            "WebsiteBugLink"    => isset($setting['websiteBugLink'])    ?   $setting['websiteBugLink']      : null,
            "MusicBeeMugLink"   => isset($setting['musicbeeBugLink'])   ?   $setting['musicbeeBugLink']     : null,
            "DonationLink"      => isset($setting['paypalDonationLink'])?   $setting['paypalDonationLink']  : null,
        ];

        if($link_key != null){
            if(isset($link[$link_key]))
            {
                return $link[$link_key]['value'];
            } else {
                return null;
            }
        }

        return $link;
    }

    private static function getSetting()
    {
        global $db_info;

        try
        {
            $sql = <<<SQL
SELECT * FROM {$db_info['settings_tbl']}
SQL;
            $statement = self::getDatabaseConnection()->prepare($sql);
            $statement->execute();
            return array_map('reset',  $statement->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_ASSOC));
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}