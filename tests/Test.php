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

/**
 * Created by PhpStorm.
 * User: Avik
 * Date: 18-01-2017
 * Time: 08:40 PM
 */

namespace tests;


use basicTest;


class Test extends \PHPUnit_Framework_TestCase
{
    public function test_sum()
    {
        $this->assertEquals(4, (2+2), "sum of two");
    }
}
