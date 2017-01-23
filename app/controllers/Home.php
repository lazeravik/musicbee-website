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

namespace App\Controllers;

use App\Lib\Controller;
use App\Lib\View;
use App\Lib\Model;

class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //create the model!
        $this->model("Home");

        $data = null;

        //Create view
        $this->view = new View();
        $this->view->renderView("home", $data);
    }
}
