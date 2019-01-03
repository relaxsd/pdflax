<?php

use Relaxsd\Pdflax\Contracts\CurrencyFormatterInterface;
use Relaxsd\Pdflax\Helpers\CurrencyFormatter;
use PHPUnit\Framework\TestCase;

class CurrencyFormatterTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Relaxsd\Pdflax\Helpers\CurrencyFormatter
     */
    protected $currencyFormatter;

    protected function setUp()
    {
        parent::setUp();

        $this->currencyFormatter = new CurrencyFormatter();

    }

    /**
     * @test
     */
    public function it_has_a_static_constructor()
    {
        // Create a default object through the constructor
        $currencyFormatter = CurrencyFormatter::create();

        // Check that we get the same default object
        $this->assertEquals($this->currencyFormatter, $currencyFormatter);
    }

    /**
     * @test
     */
    public function it_returns_a_formatted_string()
    {
        // Check that we get the same default object
        $this->assertEquals('€ 1.234,00', $this->currencyFormatter->euro(1234));
    }

    /**
     * @test
     */
    public function it_sets_options()
    {
        $self = $this->currencyFormatter->setOptions([
            CurrencyFormatterInterface::OPTION_EURO_SYMBOL    => 'EUR',
            CurrencyFormatterInterface::OPTION_DECIMALS       => 3,
            CurrencyFormatterInterface::OPTION_THOUSANDS_SEP  => ',',
            CurrencyFormatterInterface::OPTIONS_DECIMAL_POINT => '.'
        ]);

        // Assert fluent interface
        $this->assertSame($this->currencyFormatter, $self);

        // Check that we get the same default object
        $this->assertEquals('EUR 1,000.000', $this->currencyFormatter->euro(1000));
    }
}
