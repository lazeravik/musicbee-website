<?php
use App\Lib\MBReleaseManager;
use App\Lib\MBReleaseType;

function getStableReleasedata()
{
    $data = MBReleaseManager::getMusicBeeRelease(MBReleaseType::STABLE);

    if(is_a($data, "Exception")) {
        return [
            "name"          => "NA",
            "version"       => "NA",
            "supported_os"  => "NA",
            "download_link" => "NA",
            "release_date"  => "NA",
        ];
    } else {
        return [
            "name" => $data->getName(),
            "version" => $data->getVersion(),
            "supported_os" => $data->getSupportedOs(),
            "download_link" => $data->getDownloadLinks(),
            "release_date" => $data->getReleaseDate(),
        ];
    }
}

function getPatchData()
{
    $data = MBReleaseManager::getMusicBeeRelease(MBReleaseType::PATCH);

    if(is_a($data, "Exception")) {
        return [
            "name"          => "NA",
            "version"       => "NA",
            "supported_os"  => "NA",
            "download_link" => "NA",
            "release_date"  => "NA",
        ];
    } else {
        return [
            "name" => $data->getName(),
            "version" => $data->getVersion(),
            "supported_os" => $data->getSupportedOs(),
            "download_link" => $data->getDownloadLinks(),
            "release_date" => $data->getReleaseDate(),
        ];
    }
}