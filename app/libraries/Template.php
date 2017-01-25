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

namespace App\Lib\Utility;

class Template
{
    private $templateName;
    private $dataArray = [];

    /**
     * Templete initialize
     * Template constructor.
     * @param $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = strtolower($templateName);
    }

    /**
     * set an array of data that is going to be passed on the View
     * @param $variable
     * @param $data
     */
    public function setData($variable, $data)
    {
        $this->dataArray[$variable] = $data;
    }

    /**
     * Render the Template to screen
     */
    public function render()
    {
        var_dump($this->templateName);
        $setting    = setting();
        $link       = path();
        $template   = $this;
        $file       = path('template-dir').$this->templateName.'.template.php';

        if (file_exists($file)) {
            include_once $file;
        }
    }

    /**
     * Access a value of the data array
     * @param $variableName
     * @return mixed|null
     */
    public function getData($variableName)
    {
        if (!empty($this->dataArray[$variableName])) {
            return $this->dataArray[$variableName];
        } else {
            return null;
        }
    }
}
