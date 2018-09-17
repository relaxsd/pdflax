<?php

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
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return mixed
     */
    public static function toRGB($r, $g = null, $b = null)
    {

        if (is_array($r)) {
            // Does not work for less than 3 elements
            //list($r, $g, $b) = $r;
            return call_user_func_array(['Pdflax\Color', 'toRGB'], $r);
        } else if (is_string($r)) {
            return self::getColorFromTable($r);
        } else if (is_int($r)) {
            return isset($g)
                ? [$r, $g, $b]
                : [$r, $r, $r];
        }

        return null;
    }

    /**
     * @param $color
     *
     * @return array|null
     */
    public static function getColorFromTable($color)
    {
        return (array_key_exists($color, self::$COLORS))
            ? self::$COLORS[$color]
            : null;
    }

}
