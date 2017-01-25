<?php
namespace App\Transphporm\Module;

use Transphporm\TSSFunction;

class GetTextFunction implements TssFunction {
    public function run(array $args, \DomElement $element) {
        $tagremove = new TagRemover(TagType::PHP);
        $text = $tagremove->remove($element->firstChild->nodeValue);

        return __($text);
    }
}