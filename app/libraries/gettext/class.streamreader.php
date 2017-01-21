<?php


namespace Gettext\Reader;
// Simple class to wrap file streams, string streams, etc.
// seek is essential, and it should be byte stream
class StreamReader {
    // should return a string [FIXME: perhaps return array of bytes?]
    function read($bytes) {
        return false;
    }

    // should return new position
    function seekto($position) {
        return false;
    }

    // returns current position
    function currentpos() {
        return false;
    }

    // returns length of entire stream (limit for seekto()s)
    function length() {
        return false;
    }
};