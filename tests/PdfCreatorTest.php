<?php

use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface as Options;
use PHPUnit\Framework\TestCase;

class PdfCreatorTest extends TestCase
{

    /**
     * The test subject (a stub)
     *
     * @var \Relaxsd\Pdflax\Creator\PdfCreator|PHPUnit_Framework_MockObject_MockObject
     */
    protected $stub;

    protected function setUp()
    {
        parent::setUp();

        $this->stub = $this->getMockForAbstractClass('Relaxsd\Pdflax\Creator\PdfCreator');
    }

    /**
     * @test
     */
    public function it_saves_options()
    {
        $this->stub->setOption('key1', 'value1');
        $this->assertEquals($this->stub->getOption('key1'), 'value1');
        $this->assertEquals($this->stub->getOptions(), [
            'key1' => 'value1'
        ]);

        $this->stub->setOption('key2', 'value2');
        $this->assertEquals($this->stub->getOption('key2'), 'value2');
        $this->assertEquals($this->stub->getOptions(), [
            'key1' => 'value1',
            'key2' => 'value2'
        ]);

    }

    /**
     * @test
     */
    public function it_sets_portrait_and_landscape()
    {
        $this->stub->portrait();
        $this->assertEquals($this->stub->getOption('orientation'), Options::ORIENTATION_PORTRAIT);

        $this->stub->landscape();
        $this->assertEquals($this->stub->getOption('orientation'), Options::ORIENTATION_LANDSCAPE);
    }

    /**
     * @test
     */
    public function it_sets_units()
    {
        $this->stub->usingMillimeters();
        $this->assertEquals($this->stub->getOption('units'), Options::UNIT_MM);

        $this->stub->usingCentimeters();
        $this->assertEquals($this->stub->getOption('units'), Options::UNIT_CM);

        $this->stub->usingInches();
        $this->assertEquals($this->stub->getOption('units'), Options::UNIT_INCH);

        $this->stub->usingPoints();
        $this->assertEquals($this->stub->getOption('units'), Options::UNIT_PT);

        $this->stub->withUnits('km');
        $this->assertEquals($this->stub->getOption('units'), 'km');
    }

    /**
     * @test
     */
    public function it_sets_pagesize()
    {
        $this->stub->pageSizeA4();
        $this->assertEquals($this->stub->getOption('size'), Options::SIZE_A4);

        $this->stub->withPageSize('A6');
        $this->assertEquals($this->stub->getOption('size'), 'A6');

        $this->stub->withPageSize(10, 20);
        $this->assertEquals($this->stub->getOption('size'), [10, 20]);
    }

}
