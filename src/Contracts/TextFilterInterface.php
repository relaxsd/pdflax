<?php

namespace Relaxsd\Pdflax\Contracts;

/**
 * Interface for filtering text, e.g.:
 * - converting UTF-8 to ISO-8859-1 for PDF implementations that do not support UTF-8.
 *
 * @param $str  The input text
 *
 * @return string The filtered text
 */
interface TextFilterInterface
{

    /**
     * Filters/translates a string.
     *
     * @param string $str The input string
     *
     * @return string The output string
     */
    public function filter($str);

}
