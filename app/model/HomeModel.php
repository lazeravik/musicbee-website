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
use App\Lib\MBReleaseManager;
use App\Lib\MBReleaseType;
use App\Lib\Model;

class HomeModel extends Model
{
    public function __construct(){}

    public function getReleasedata()
    {
        $data = MBReleaseManager::getMusicBeeRelease(MBReleaseType::STABLE);
        return [
            "name"          => $data->getName(),
            "version"       => $data->getVersion(),
            "supported_os"  => $data->getSupportedOs(),
            "download_link" => $data->getDownloadLinks(),
        ];
    }
}