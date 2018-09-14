<?php

use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Pdflax\Registry\Registry
     */
    protected $registry;

    protected function setUp()
    {
        parent::setUp();

        $this->registry = new \Pdflax\Registry\Registry();
    }

    /**
     * @test
     * @expectedException \Pdflax\Registry\RegistryException
     */
    public function it_excepts_when_key_is_missing()
    {
        $this->registry->getValue(null);
    }

    /**
     * @test
     * @expectedException \Pdflax\Registry\RegistryException
     */
    public function it_excepts_when_no_values_are_present()
    {
        $this->registry->getValue('invalidKey');
    }

    /**
     * @test
     * @expectedException \Pdflax\Registry\RegistryException
     */
    public function it_excepts_when_key_is_not_found()
    {
        $this->registry->register('key', 'value');

        $this->registry->getValue('otherKey');
    }

    /**
     * @test
     */
    public function it_registers_a_value()
    {
        $this->registry->register('key', 'value');

        $this->assertEquals($this->registry->getValue('key'), 'value');
    }

    /**
     * @test
     */
    public function it_registers_multiple_values()
    {
        $this->registry->register('key1', 'value1');
        $this->registry->register('key2', 'value2');

        $this->assertEquals($this->registry->getValue('key1'), 'value1');
        $this->assertEquals($this->registry->getValue('key2'), 'value2');
    }

}
