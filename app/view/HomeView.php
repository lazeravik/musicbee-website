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

namespace App\View;

use App\Lib\View;

class HomeView extends View
{
    public function render()
    {
        $data = [
            "menu" => getMenuHtml(),
            "footer" => getFooterHtml(),
            "release" => getStableReleasedata(),
        ];

        echo $this->buildTemplate($data, "home.tss");
    }
}