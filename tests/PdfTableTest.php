<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Table\PdfTable;
use Relaxsd\Stylesheets\Style;
use Relaxsd\Stylesheets\Stylesheet;

class PdfTableTest extends TestCase
{

    /**
     * The test subject
     *
     * @var Relaxsd\Pdflax\Table\PdfTable
     */
    protected $pdfTable;

    /**
     * @var \Relaxsd\Pdflax\Contracts\PdfDocumentInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $pdfMock;

    protected function setUp()
    {
        parent::setUp();

        // Create a (mock) PDF document
        $this->pdfMock = $this->getMockBuilder('Relaxsd\Pdflax\Contracts\PdfDocumentInterface')->getMock();

        // Create a view in that document, at 10,20.
        $this->pdfTable = new PdfTable($this->pdfMock, 10, 20, 25, 50);

        // Within the table, use 100x100 as coordinate system.
        // This will correspond to the witdh (25) and height (50) of the pdf (see above).
        $this->pdfTable->setReferenceSize(100, 100);

    }

    /**
     * @test
     */
    public function it_accepts_a_size_in_percentage()
    {

        // Let the pdf tell the view that its size is 100x200
        $this->pdfMock->expects($this->atLeastOnce())->method('getInnerWidth')->willReturn(100);
        $this->pdfMock->expects($this->atLeastOnce())->method('getInnerHeight')->willReturn(200);

        // Create a table in that document, at 10,20, 50% of parent width/height (50x100).
        $this->pdfTable = new PdfTable($this->pdfMock, 10, 20, '50%', '50%');
        $this->assertEquals(50, $this->pdfTable->getWidth());
        $this->assertEquals(100, $this->pdfTable->getHeight());
    }

    /**
     * @test
     */
    public function it_adjusts_styles_when_chaining_reference_size()
    {

        $this->pdfTable->addStylesheet(new Stylesheet([
            'style' => [
                'font-size' => 10
            ]
        ]));
        $this->pdfTable->setReferenceSize(200, 200, true);

        $expected = (new Style([
            'font-size' => 20
        ]));
        $this->assertEquals($expected, $this->pdfTable->getStyle('style'));
    }

    /**
     * @test
     */
    public function it_adds_rows()
    {

        $row = $this->pdfTable->row();

        $this->assertInstanceOf('\Relaxsd\Pdflax\Table\PdfTableRow', $row);
        $this->assertEquals(0.0, $row->getY());
        $this->assertEquals(10.0, $row->getHeight());
        $this->assertNotNull($row->getStyle('td'));
        $this->assertNotNull($row->getStyle('th'));

        $row = $this->pdfTable->row(20, ['extra' => ['font-size' => 8]]);

        $this->assertInstanceOf('\Relaxsd\Pdflax\Table\PdfTableRow', $row);
        $this->assertEquals(10.0, $row->getY());
        $this->assertEquals(20.0, $row->getHeight());
        $this->assertNotNull($row->getStyle('extra'));
    }

    /**
     * @test
     */
    public function it_adds_columns()
    {

        $self = $this->pdfTable->column(10);

        // Assert fluent interface
        $this->assertSame($this->pdfTable, $self);

        // Assert column 1 values
        $this->assertEquals(1, $this->pdfTable->getColumnCount());
        $column = $this->pdfTable->getColumn(0);
        $this->assertEquals(0.0, $column->getX());
        $this->assertEquals(10.0, $column->getWidth());

        // Assert column 2 values
        $this->pdfTable->column(19, ['extra' => ['font-size' => 8]]);
        $this->assertEquals(2, $this->pdfTable->getColumnCount());
        $column = $this->pdfTable->getColumn(1);
        $this->assertEquals(10.0, $column->getX());
        $this->assertEquals(19.0, $column->getWidth());
        $this->assertNotNull($column->getStyle('extra'));

    }

}
