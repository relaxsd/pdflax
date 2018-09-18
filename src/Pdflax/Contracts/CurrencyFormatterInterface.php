<?php

namespace Pdflax\Contracts;

interface CurrencyFormatterInterface
{

    const OPTION_EURO_SYMBOL = 'euro';
    const OPTION_DECIMALS = 'decimals';
    const OPTION_THOUSANDS_SEP = 'thousands_sep';
    const OPTIONS_DECIMAL_POINT = 'dec_point';

    /**
     * @param float $amount
     *
     * @return string
     */
    public function euro($amount);

}
