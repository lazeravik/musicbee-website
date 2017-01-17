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
 * Date: 17-01-2017
 * Time: 05:48 PM
 */

namespace App\Lib;
use App\Lib\Utility\Enum;

abstract class MBReleaseType extends Enum
{
    const STABLE = 1;
    const BETA = 2;
    const PATCH =3;
}