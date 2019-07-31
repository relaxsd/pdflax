<?php

namespace Relaxsd\Pdflax\Helpers;

class Converter
{

    const MM_PER_POINT = 0.352777778;

    public static function points_to_mm($points)
    {
        return self::MM_PER_POINT * $points;
    }

}
