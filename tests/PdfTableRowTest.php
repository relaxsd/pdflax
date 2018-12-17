<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Table\PdfTableRow;
use Relaxsd\Stylesheets\Style;

class PdfTableRowTest extends TestCase
{

    /**
     * The test subject
     *
     * @var Relaxsd\Pdflax\Table\PdfTableRow
     */
    protected $pdfRow;

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
        $this->pdfRow = new PdfTableRow($this->pdfTableMock, 10, 20, ['extra' => ['name' => 'value']]);

    }

    /**
     * @test
     */
    public function it_is_instantiatable()
    {
        $this->assertEquals(10, $this->pdfRow->getY());
        $this->assertEquals(20, $this->pdfRow->getHeight());
        $this->assertEquals(new Style(['name' => 'value']), $this->pdfRow->getStyle('extra'));
        $this->assertInstanceOf('Relaxsd\Stylesheets\Stylesheet', $this->pdfRow->getStylesheet());
    }

    /**
     * @test
     */
    public function it_accepts_a_size_in_percentage()
    {

        // Let the pdf tell the view that its size is 100x200
        $this->pdfTableMock->expects($this->atLeastOnce())->method('getLocalHeight')->willReturn(200);

        // Create a table in that document, at 10,20, 50% of parent width/height (50x100).
        $this->pdfRow = new PdfTableRow($this->pdfTableMock, '25%', '50%', ['extra' => ['name' => 'value']]);
        $this->assertEquals('25%', $this->pdfRow->getY());
        $this->assertEquals(50.0, $this->pdfRow->getY(true));
        $this->assertEquals('50%', $this->pdfRow->getHeight());
        $this->assertEquals(100.0, $this->pdfRow->getHeight(true));
    }

    /**
     * @test
     */
    public function it_creates_table_cells()
    {

        // Create a (mock) column
        $columnMock = $this->getMockBuilder('Relaxsd\Pdflax\Table\PdfTableColumn')
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();

        $columnMock->expects($this->atLeastOnce())
            ->method('getStyle')
            ->with('td')
            ->willReturn(new Style(['columnValue-for-td' => 9]));

        $this->pdfTableMock->expects($this->atLeastOnce())->method('getColumn')->willReturn($columnMock);
        $this->pdfTableMock->expects($this->exactly(2))->method('setCursorXY')->withConsecutive([0, 10], [0, 20]);
        $this->pdfTableMock->expects($this->once())->method('cell')->with(0.0, 20, 'CAPTION', new Style([
            'border'             => 1,
            'fill'               => 0,
            'fill-color'         => 0,
            'columnValue-for-td' => 9
        ]));

        $self = $this->pdfRow->td('CAPTION');

        // Assert fluent interface
        $this->assertSame($this->pdfRow, $self);

    }

    /**
     * @test
     */
    public function it_creates_table_headings()
    {

        // Create a (mock) column
        $columnMock = $this->getMockBuilder('Relaxsd\Pdflax\Table\PdfTableColumn')
            ->disableOriginalConstructor()
            ->disableProxyingToOriginalMethods()
            ->getMock();

        $columnMock->expects($this->atLeastOnce())
            ->method('getStyle')
            ->withConsecutive(['th'], ['td'])
            ->willReturnOnConsecutiveCalls(
                new Style(['columnValue-for-th' => 1]),
                new Style(['columnValue-for-td' => 2])
            );

        $this->pdfTableMock->expects($this->atLeastOnce())->method('getColumn')->willReturn($columnMock);
        $this->pdfTableMock->expects($this->exactly(2))->method('setCursorXY')->withConsecutive([0, 10], [0, 20]);
        $this->pdfTableMock->expects($this->once())->method('cell')->with(0.0, 20, 'CAPTION', new Style([
            'border'             => 1,
            'columnValue-for-td' => 2,
            'columnValue-for-th' => 1,
            'fill'               => 1,
            'fill-color'         => 200,
            'font-style'         => 'bold',
        ]));

        $self = $this->pdfRow->th('CAPTION');

        // Assert fluent interface
        $this->assertSame($this->pdfRow, $self);

    }

}
