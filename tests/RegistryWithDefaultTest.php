<?php

use PHPUnit\Framework\TestCase;

class RegistryWithDefaultTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Pdflax\Registry\RegistryWithDefault
     */
    protected $registry;

    protected function setUp()
    {
        parent::setUp();

        $this->registry = new \Pdflax\Registry\RegistryWithDefault();
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

    // =======================

    /**
     * @test
     * @expectedException \Pdflax\Registry\RegistryException
     */
    public function it_excepts_when_there_is_no_default_key()
    {
        $this->registry->getDefaultValue();
    }

    /**
     * @test
     * @expectedException \Pdflax\Registry\RegistryException
     */
    public function it_excepts_when_a_value_is_not_registered_as_default()
    {
        $this->registry->register('key', 'value', false);

        $this->registry->getDefaultValue();
    }

    /**
     * @test
     */
    public function it_registers_a_default_value_automatically()
    {
        $this->registry->register('key', 'value');

        $this->assertEquals($this->registry->getDefaultValue(), 'value');
    }

    /**
     * @test
     */
    public function it_registers_a_default_value()
    {
        $this->registry->register('key', 'value', true);

        $this->assertEquals($this->registry->getDefaultValue(), 'value');
    }

    /**
     * @test
     */
    public function it_registers_a_default_of_multiple_values()
    {
        $this->registry->register('key1', 'value1', false);
        $this->registry->register('key2', 'value2', true);
        $this->registry->register('key3', 'value2' /*...*/);

        $this->assertEquals($this->registry->getDefaultValue(), 'value2');
    }

}
