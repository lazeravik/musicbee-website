<?php
namespace App\Transphporm\Module;

use Transphporm\Module;
use Transphporm\Config as Transfig;
use App\Transphporm\Module\GetTextFunction;

class GetTextModule implements Module
{
    public function load(Transfig $transfig) {
        $transfig->getFunctionSet()->addFunction('__', new GetTextFunction);
    }
}