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

use App\Lib\Help;
use App\Lib\Model;
use App\Lib\Settings;
use App\Lib\Utility\LanguageManager;

class ApiModel extends Model
{
    private $help = null;

    public function __construct()
    {
        $helpInstance = new Help();
        $this->help = $helpInstance->getHelp();
    }

    public function getMbApi()
    {
        $content = $this->help['api_html']['data'];

        if ($content !== false) {
            return $content;
        }

        return null;
    }
}