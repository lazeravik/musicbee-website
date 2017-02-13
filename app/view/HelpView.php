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

class HelpView extends View
{
    public function render()
    {
        $data = [
            "menu" => getMenuHtml(),
            "footer" => getFooterHtml(),
            "meta" => [
                "title" => lang("help_page_title"),
                "desc" => lang("help_page_desc"),
            ],

            "page" => [
                "content"   => $this->getModel()->getHelpPageContent(),
            ],
        ];

        echo $this->buildTemplate($data, "help.tss");
    }
}