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

class CreditView extends View
{
    public function render()
    {
        $data = [
            "menu"      => getMenuHtml(),
            "footer"    => getFooterHtml(),
            "meta"      =>
                [
                    "title" => lang("credit_page_title"),
                    "desc" => lang("credit_page_desc"),
                ],

            "githublink"    => setting("github_link"),
            "webver"        => setting("version"),

        ];

        echo $this->buildTemplate($data, "credit.tss");
    }
}