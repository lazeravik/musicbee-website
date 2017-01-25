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

use App\Lib\Utility\LanguageManager;
use App\Lib\View;
use App\Lib\ForumHook;
use Transphporm\Builder;
use App\Transphporm\Module\GetTextModule;
use App\Transphporm\Module\SprintfModule;

class HomeView extends View
{
    public function render()
    {
        $sdfsrf= __("Sdfgsdfg");
        $locale = LanguageManager::getLocale();
        $home_tss = path("view-dir") . "tss/home.tss";
        $layout_xml = path("view-dir") . "templates/layout.html";

        $data = new data;

        $page_tpl = new Builder($layout_xml, $home_tss);
        $page_tpl->loadModule(new GetTextModule());
        $page_tpl->loadModule(new SprintfModule());

        $out = $page_tpl->output($data)->body;


        echo ($out);

        //$this->template->setData("releasedata", $this->model->getReleasedata());
        //$this->template->render();

    }
}

class data {
    public function __($string){
        return __($string);
    }



    public function setting($string = null)
    {
        return setting($string);
    }

    public function path($pathName = null)
    {
        return path($pathName);
    }
}