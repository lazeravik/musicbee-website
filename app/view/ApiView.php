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

class ApiView extends View
{
    public function render()
    {
        $model = $this->getModel();

        $data = [
            "menu"      => getMenuHtml(),
            "footer"    => getFooterHtml(),
            "meta"      =>
                [
                    "title" => lang("api_page_title"),
                    "desc" => lang("api_page_desc"),
                ],
            "page"      => [
                    "mbapi" => $model->getMbApi(),
            ],
        ];

        echo $this->buildTemplate($data, "api.tss");
    }
}