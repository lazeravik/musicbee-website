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

use App\Lib\Model;
use App\Lib\Settings;

class HelpModel extends Model
{
    public function __construct(){}

    public function getHelpPageContent()
    {
        try{
            $wikiaLink = Settings::getLinks("wikiaLink").'/index.php?action=render&title=FAQ';
            $content = file_get_contents($wikiaLink);
            return $content;
        } catch (\Exception $e){
            return null;
        }

    }
}