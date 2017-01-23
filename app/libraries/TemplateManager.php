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

use App\Lib\Utility\Template;

class TemplateManager
{
    private static $template;

    /**
     * Set the template instance initially for future use
     * @param Template $template
     * @return \InvalidArgumentException
     */
    public static function setTemplate(Template $template)
    {
        if (empty($template) || !$template instanceof Template) {
            return new \InvalidArgumentException("Template is not valid!");
        }

        self::$template = $template;
    }

    /**
     * Gets data from array that is assigned inside template
     * @param $variableName
     * @return \InvalidArgumentException|null|string
     */
    public static function getData($variableName)
    {
        if (empty($variableName) || empty(self::$template)) {
            return new \InvalidArgumentException("Data Variable name can not be empty!");
        }

        $data = self::$template->getData($variableName);
        if (!empty($data)) {
            return self::$template->getData($variableName);
        } else {
            return null;
        }

    }
}
