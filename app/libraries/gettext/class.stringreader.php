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

namespace Gettext\Reader;

class StringReader
{
    var $_pos;
    var $_str;

    function StringReader($str = '')
    {
        $this->_str = $str;
        $this->_pos = 0;
    }

    function read($bytes)
    {
        $data = substr($this->_str, $this->_pos, $bytes);
        $this->_pos += $bytes;
        if (strlen($this->_str) < $this->_pos)
            $this->_pos = strlen($this->_str);

        return $data;
    }

    function seekto($pos)
    {
        $this->_pos = $pos;
        if (strlen($this->_str) < $this->_pos)
            $this->_pos = strlen($this->_str);
        return $this->_pos;
    }

    function currentpos()
    {
        return $this->_pos;
    }

    function length()
    {
        return strlen($this->_str);
    }

}