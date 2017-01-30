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
use App\Transphporm\Module\PathModule;
use Transphporm\Builder;
use App\Transphporm\Module\GetTextModule;
use App\Transphporm\Module\SprintfModule;
//use SimpleCache\SimpleCache;

class HomeView extends View
{
    public function render()
    {
        $home_tss = path("view-dir") . "tss/home.tss";
        $layout_xml = path("view-dir") . "templates/layout.html";

        $data = [
            "menu" => getMenuHtml(),
            "footer" => getFooterHtml(),
            "release" => getStableReleasedata(),
        ];


        //include_once path("root").'libraries/SimpleCache.php';

        $page_tpl = new Builder($layout_xml, $home_tss);
        $page_tpl->loadModule(new GetTextModule());
        $page_tpl->loadModule(new SprintfModule());
        $page_tpl->loadModule(new PathModule());
        //$page_tpl->setCache($cache);

        $out = $page_tpl->output($data)->body;
        echo ($out);
    }
}