<?php
namespace App\Transphporm\Module;

use Transphporm\TSSFunction;
use App\Transphporm\Module\TagRemover;

class SprintfFunction implements TssFunction {

    private $isArryArgs = false;
    private $isGettext = false;

    function __construct($isArryArgs = false, $isGettext = false)
    {
        $this->isArryArgs = $isArryArgs;
        $this->isGettext = $isGettext;
    }

    public function run(array $args, \DomElement $element)
    {
        $tagremove = new TagRemover(TagType::PHP);
        $text = $tagremove->remove($element->firstChild->nodeValue);
        $text = ($this->isGettext)? __($text) : $text;

        if($this->isArryArgs) {
            $text = vsprintf($text, $args);
        } else {
            $text = sprintf($text, $args[0]);
        }

        return ($this->isGettext)? __($text) : $text;
    }
}