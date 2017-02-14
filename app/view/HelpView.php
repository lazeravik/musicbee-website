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
        $model = $this->getModel();

        $data = [
            "menu"      => getMenuHtml(),
            "footer"    => getFooterHtml(),
            "meta"      =>
                [
                    "title" => lang("help_page_title"),
                    "desc" => lang("help_page_desc"),
                ],

            "page" => [
                "content"               =>$model->getHelpPageContent(),
                "sidebar_userhelp"      =>$model->getSidebarUserHelp(),
                "sidebar_devhelp"       =>$model->getSidebarDevHelp(),
                "sidebar_wiki_popular"  =>$model->getWikiaPopularPosts(),
                "sidebar_wiki_viewed"   =>$model->getWikiaMostViewedPosts(),
                "sidebar_wiki_new"      =>$model->getWikiaNewPosts(),
                "top_help_palette"      =>$model->getTopHelpPalette(),
            ],
        ];

        echo $this->buildTemplate($data, "help.tss");
    }
}