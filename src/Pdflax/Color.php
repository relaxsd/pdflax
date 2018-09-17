<?php
/**
 * Created by PhpStorm.
 * User: Martijn
 * Date: 17-9-2018
 * Time: 14:30
 */

namespace Pdflax;

class Color
{

    const BLACK = 'black';
    const WHITE = 'white';
    const RED = 'red';

    /**
     * @var array
     */
    protected static $COLORS = [
        self::BLACK => [0, 0, 0],
        self::WHITE => [255, 255, 255],
        self::RED   => [255, 0, 0],
    ];

    /**
     * @param $color
     *
     * @return array|null
     */
    public static function toRGB($color)
    {
        return array_key_exists($color, self::$COLORS)
            ? self::$COLORS[$color]
            : null;
    }

}
