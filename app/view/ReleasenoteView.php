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

class ReleasenoteView extends View
{
    public function render()
    {
        $model = $this->getModel();
var_dump($model->getReleaseNote());
        $data = [
            "menu"      => getMenuHtml(),
            "footer"    => getFooterHtml(),
            "meta"      =>
                [
                    "title" => lang("releasenote_page_title"),
                    "desc" => lang("releasenote_page_desc"),
                ],
            "page"      => [
                    "releasenote" => $model->getReleaseNote(),
            ],
        ];

        echo $this->buildTemplate($data, "releasenote.tss");
    }
}