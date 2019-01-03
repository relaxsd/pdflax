<?php

namespace Relaxsd\Pdflax\Helpers;

class Arr
{

    public static function mergeRecursiveConfig()
    {

        $arrays = func_get_args();
        $result = [];
        while ($arrays) {

            $array = array_shift($arrays);

            foreach ($array as $key => $value) {

                if (is_string($key)) {
                    if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                        $result[$key] = self::mergeRecursiveConfig($result[$key], $value);
                    } else {
                        // TODO: This overwrites the old (non-array) value
                        $result[$key] = $value;
                    }
                } else {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

}
