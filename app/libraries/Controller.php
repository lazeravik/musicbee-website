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


class Controller
{
    protected $view;
    protected $model;
    public function __construct(){}
    public function index(){}

    public function loadModel($modelName)
    {
        $file = 'model/' . $modelName . '_model.php';
        if(file_exists($file)) {
            require_once $file;

            $namespace = "App\\Lib\\Model\\".$modelName."_model";
            $this->model = new $namespace();
        }
    }
}