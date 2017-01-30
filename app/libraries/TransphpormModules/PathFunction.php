<?php
namespace App\Transphporm\Module;

use Transphporm\TSSFunction;

class PathFunction implements TssFunction {

    private $isAppend;
    function __construct($isAppend = false)
    {
        $this->isAppend = $isAppend;
    }

    public function run(array $args, \DomElement $element)
    {
        $path = null;
        if($this->isAppend == true && count($args) == 2)
        {
            if(array_key_exists($args[0], path())) {
                return path($args[0]) . $element->getAttributeNode($args[1])->value;
            }
        }

        $path = (count($args) > 0)? path($args[0]) : path();
        return $path;
    }
}