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

class FileReader
{
    var $_pos;
    var $_fd;
    var $_length;

    function __construct($filename)
    {
        if (file_exists($filename)) {
            $this->_length = filesize($filename);
            $this->_pos = 0;
            $this->_fd = fopen($filename, 'rb');


            if (!$this->_fd) {
                $this->error = 3; // Cannot read file, probably permissions
                return false;
            }
        } else {
            $this->error = 2; // File doesn't exist
            return false;
        }
    }

    function read($bytes)
    {
        if ($bytes) {
            fseek($this->_fd, $this->_pos);

            // PHP 5.1.1 does not read more than 8192 bytes in one fread()
            // the discussions at PHP Bugs suggest it's the intended behaviour
            $data = '';
            while ($bytes > 0) {
                $chunk = fread($this->_fd, $bytes);
                $data .= $chunk;
                $bytes -= strlen($chunk);
            }
            $this->_pos = ftell($this->_fd);

            return $data;
        } else return '';
    }

    function seekto($pos)
    {
        fseek($this->_fd, $pos);
        $this->_pos = ftell($this->_fd);
        return $this->_pos;
    }

    function currentpos()
    {
        return $this->_pos;
    }

    function length()
    {
        return $this->_length;
    }

    function close()
    {
        fclose($this->_fd);
    }

}