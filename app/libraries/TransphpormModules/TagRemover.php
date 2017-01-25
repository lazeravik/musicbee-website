<?php
namespace App\Transphporm\Module;

class TagRemover
{
    private $tagType;
    function __construct($tagType = TagType::PHP)
    {
        $this->tagType = $tagType;
    }

    public function remove($string)
    {
        switch ($this->tagType) {
            case TagType::PHP:
                return $this->doRemovePhp($string);
                break;
            default:
                return $this->doRemovePhp($string);
                break;
        }


    }

    private function doRemovePhp($string)
    {

        /**
         * replace tag starts with: <?= __(
         */
        $txt = str_replace(
            "= __(\"",
            "",
            $string
        );
        $txt = str_replace(
            "__(\"",
            "",
            $txt
        );

        /**
         * replace tags ends with: ); ?>
         */
        $txt = str_replace(
            "\"); ?>",
            "",
            $txt
        );

        $txt = str_replace(
            "\"); ?",
            "",
            $txt
        );
        return $txt;
    }
}