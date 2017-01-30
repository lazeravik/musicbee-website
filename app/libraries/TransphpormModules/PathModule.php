<?php
namespace App\Transphporm\Module;

use Transphporm\Module;
use Transphporm\Config as Transfig;

class PathModule implements Module
{
    public function load(Transfig $transfig) {
        $transfig->getFunctionSet()->addFunction('path', new PathFunction());
        $transfig->getFunctionSet()->addFunction('append_path', new PathFunction(true));
    }
}