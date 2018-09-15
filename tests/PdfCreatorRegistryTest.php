<?php

use Pdflax\Creator\PdfCreator;
use Pdflax\Creator\PdfCreatorRegistry;
use PHPUnit\Framework\TestCase;



class PdfCreatorRegistryTest extends TestCase
{

    /**
     *
     *
     * @var \Pdflax\Registry\RegistryWithDefault|PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * The test subject
     *
     * @var \Pdflax\Creator\PdfCreatorRegistry
     */
    protected $pdfCreatorRegistry;

    protected function setUp()
    {
        parent::setUp();

        $this->registryMock = $this->getMockBuilder('\Pdflax\Registry\RegistryWithDefault')->getMock();

        $this->pdfCreatorRegistry = new PdfCreatorRegistry($this->registryMock);
    }

    /**
     * @test
     */
    public function it_stores_the_implementation()
    {
        $result = $this->pdfCreatorRegistry->setImplementation('implementation1');
        $this->assertEquals($this->pdfCreatorRegistry->getImplementation(), 'implementation1');
        $this->assertSame($result, $this->pdfCreatorRegistry);
    }

    /**
     * @test
     */
    public function it_registers_implementations()
    {
        $this->registryMock
            ->expects($this->exactly(3))
            ->method('register')
            ->withConsecutive(
                ['implementation1', 'className1', false],
                ['implementation1', 'className1', true],
                ['implementation1', 'className1', null]
            );

        $this->pdfCreatorRegistry->register('implementation1', 'className1', false);
        $this->pdfCreatorRegistry->register('implementation1', 'className1', true);
        $result = $this->pdfCreatorRegistry->register('implementation1', 'className1');
        $this->assertSame($result, $this->pdfCreatorRegistry);

    }

    /**
     * @test
     */
    public function it_creates_a_document()
    {
        // Mock our registry to return 'PDF_CREATOR_CLASS' as default
        $this->registryMock
            ->expects($this->once())
            ->method('getDefaultValue')
            ->willReturn('PDF_CREATOR_CLASS');

        // Mock a PdfCreator (normally abstract) to return a 'PDF_DOCUMENT'
        $pdfCreator = $this->getMock('Pdflax\Creator\PdfCreator', ['create']);
        $pdfCreator->expects($this->once())
            ->method('create')
            ->with(['key1' => 'value1'])
            ->willReturn('PDF_DOCUMENT');

        // Mock our pdfCreatorRegistry to intercept its createInstance() call,
        // we will return our PdfCreator mock instead
        $pdfCreatorRegistry = $this->getMock('Pdflax\Creator\PdfCreatorRegistry', ['createInstance'], [$this->registryMock]);
        $pdfCreatorRegistry->expects($this->once())
            ->method('createInstance')
            ->with('PDF_CREATOR_CLASS')
            ->will($this->returnValue($pdfCreator));

        self::assertEquals('PDF_DOCUMENT', $pdfCreatorRegistry->create(['key1' => 'value1']));

    }

    /**
     * @test
     */
    public function it_creates_an_instance()
    {
        // Mock our registry to return the name of our 'TestPdfCreator' to be instantiated
        $this->registryMock
            ->expects($this->once())
            ->method('getDefaultValue')
            ->willReturn('TestPdfCreator');

        self::assertEquals('TEST_PDF', $this->pdfCreatorRegistry->create());
    }

    /**
     * @test
     * @expectedException  Pdflax\Creator\PdfCreatorException
     */
    public function it_excepts_when_driver_not_found()
    {
        // Mock our registry to throw an exception
        $this->registryMock
            ->expects($this->once())
            ->method('getValue')
            ->with('NONEXISTENT')
            ->willThrowException(new \Pdflax\Registry\RegistryException());

        $this->pdfCreatorRegistry->setImplementation('NONEXISTENT')->create();
    }

}

// Subclass of PdfCreator to test with
class TestPdfCreator extends PdfCreator
{
    public function create($options = [])
    {
        return 'TEST_PDF';
    }
}
