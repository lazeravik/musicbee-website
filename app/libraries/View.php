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

class View
{
    public function __construct()
    {
    }

    public function renderView($templateName)
    {
        global $link, $mb, $locale, $mainmenu, $footer;

        $file = "views/$templateName.template.php";
        if (file_exists($file)) {
            require_once $file;
        } else {
            throw new \Exception("The template($templateName) required for this is not found!");
        }
    }
}
