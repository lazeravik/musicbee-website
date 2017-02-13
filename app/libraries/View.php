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

use App\Transphporm\Module\PathModule;
use Transphporm\Builder;
use App\Transphporm\Module\GetTextModule;
use App\Transphporm\Module\SprintfModule;

class View
{
    protected $model;
    protected $layout_xml;

    public function __construct(Model $model = null)
    {
        $this->model        = $model;
        $this->layout_xml   = path("view-dir") . "templates/layout.html";
    }

    public function render(){}

    protected function buildTemplate($data, $tss)
    {
        $tss = path("view-dir") . "tss/{$tss}";

        $page_tpl = new Builder($this->layout_xml, $tss);
        $page_tpl->loadModule(new GetTextModule());
        $page_tpl->loadModule(new SprintfModule());
        $page_tpl->loadModule(new PathModule());
        $out = $page_tpl->output($data)->body;

        return $out;
    }

    protected function getModel()
    {
        return $this->model;
    }
}
