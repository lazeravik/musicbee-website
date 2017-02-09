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

/**
 * Created by PhpStorm.
 * User: Avik
 * Date: 17-01-2017
 * Time: 12:04 PM
 */

namespace App\Lib;

class MBRelease
{

    private $release_version     = null;
    private $release_name        = null;
    private $release_date        = null;
    private $download_links      = null;
    private $supported_os        = null;
    private $message             = null;
    private $misc                = null;

    /**
     * MBRelease constructor. Initial values!
     * @param $release_version
     * @param $release_name
     * @param $release_date
     * @param $downloads
     * @param $supported_os
     * @param $message
     * @param $misc
     */
    public function __construct(
        $release_version,
        $release_name,
        $release_date,
        $downloads,
        $supported_os,
        $message,
        $misc
    ) {
        $this->release_version = $release_version;
        $this->release_name    = $release_name   ;
        $this->release_date    = $release_date   ;
        $this->download_links  = $downloads      ;
        $this->supported_os    = $supported_os   ;
        $this->message         = $message        ;
        $this->misc            = $misc           ;
    }


    /**
     * Returns Musicbee release version
     * @return string
     */
    public function getVersion()
    {
        return $this->release_version;
    }

    /**
     * Returns Musicbee release name
     * @return string
     */
    public function getName()
    {
        return $this->release_name;
    }

    /**
     * Returns release date for Musicbee
     * @return string
     */
    public function getReleaseDate()
    {
        return $this->release_date;
    }

    /**
     * Returns supported os of this Musicbee release
     * @return string
     */
    public function getSupportedOs()
    {
        return $this->supported_os;
    }


    public function getDownloadLinks()
    {
        return $this->download_links;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
