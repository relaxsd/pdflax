<?php

namespace Pdflax\Helpers;

use Pdflax\Contracts\CurrencyFormatterInterface;

class CurrencyFormatter implements CurrencyFormatterInterface
{
    /**
     * @param array $options
     */
    protected $options = [
        self::OPTION_EURO_SYMBOL    => '€',
        self::OPTION_DECIMALS       => 2,
        self::OPTION_THOUSANDS_SEP  => '.',
        self::OPTIONS_DECIMAL_POINT => ','
    ];

    /**
     * Currency constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Pdflax\Helpers\CurrencyFormatter
     */
    public static function create($options = [])
    {
        return new static($options);
    }

    /**
     * @param float $amount
     *
     * @return string
     */
    public function euro($amount)
    {
        return $this->options[self::OPTION_EURO_SYMBOL] . ' ' . number_format($amount,
                $this->options[self::OPTION_DECIMALS],
                $this->options[self::OPTIONS_DECIMAL_POINT],
                $this->options[self::OPTION_THOUSANDS_SEP]
            );
    }

}
