<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Table\PdfTable;
use Relaxsd\Pdflax\Table\PdfTableColumn;
use Relaxsd\Stylesheets\Style;
use Relaxsd\Stylesheets\Stylesheet;

class PdfTableColumnTest extends TestCase
{

    /**
     * The test subject
     *
     * @var Relaxsd\Pdflax\Table\PdfTableColumn
     */
    protected $pdfColumn;

    /**
     * @var \Relaxsd\Pdflax\Table\PdfTable|PHPUnit_Framework_MockObject_MockObject
     */
    protected $pdfTableMock;

    protected function setUp()
    {
        parent::setUp();

        // Create a (mock) PDF table
        $this->pdfTableMock = $this->getMockBuilder('Relaxsd\Pdflax\Table\PdfTable')
            ->disableOriginalConstructor()
            ->getMock();

        // Create a column in that table
        $this->pdfColumn = new PdfTableColumn($this->pdfTableMock, 10, 20, ['extra' => ['name' => 'value']]);

    }

    /**
     * @test
     */
    public function it_is_instantiatable()
    {
        $this->assertEquals(10, $this->pdfColumn->getX());
        $this->assertEquals(20, $this->pdfColumn->getWidth());
        $this->assertEquals(new Style(['name' => 'value']), $this->pdfColumn->getStyle('extra'));
        $this->assertInstanceOf('Relaxsd\Stylesheets\Stylesheet', $this->pdfColumn->getStylesheet());
    }

    /**
     * @test
     */
    public function it_accepts_a_size_in_percentage()
    {

        // Let the pdf tell the view that its size is 100x200
        $this->pdfTableMock->expects($this->atLeastOnce())->method('getLocalWidth')->willReturn(200);

        // Create a table in that document, at 10,20, 50% of parent width/height (50x100).
        $this->pdfColumn = new PdfTableColumn($this->pdfTableMock, '25%', '50%', ['extra' => ['name' => 'value']]);
        $this->assertEquals('25%', $this->pdfColumn->getX());
        $this->assertEquals(50.0, $this->pdfColumn->getX(true));
        $this->assertEquals('50%', $this->pdfColumn->getWidth());
        $this->assertEquals(100.0, $this->pdfColumn->getWidth(true));
    }

}
