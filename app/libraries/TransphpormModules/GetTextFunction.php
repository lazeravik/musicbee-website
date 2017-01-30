<?php
namespace App\Transphporm\Module;

use Transphporm\TSSFunction;

class GetTextFunction implements TssFunction {
    public function run(array $args, \DomElement $element) {
        $tagremove = new TagRemover(TagType::PHP);
        $text = $tagremove->remove($this->get_inner_html($element));

        //var_dump(__($text));
        return __($text);
    }

    function get_inner_html( $node ) {
        $innerHTML= '';
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML( $child );
        }

        return $innerHTML;
    }
}