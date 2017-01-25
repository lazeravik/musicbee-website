<?php

namespace App\Transphporm\Module;

use Transphporm\Module;
use Transphporm\Config as Transfig;

class SprintfModule implements Module
{
    public function load(Transfig $transfig) {
        $transfig->getFunctionSet()->addFunction('vsprintf', new SprintfFunction(true));
        $transfig->getFunctionSet()->addFunction('__vsprintf', new SprintfFunction(true, true));
        $transfig->getFunctionSet()->addFunction('sprintf', new SprintfFunction());
        $transfig->getFunctionSet()->addFunction('__sprintf', new SprintfFunction(false, true));
    }
}