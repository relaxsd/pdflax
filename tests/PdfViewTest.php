<?php

use Pdflax\PdfView;
use PHPUnit\Framework\TestCase;

class PdfViewTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Pdflax\PdfView
     */
    protected $pdfView;

    /**
     * @var \Pdflax\Contracts\PdfDocumentInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $pdfMock;

    protected function setUp()
    {
        parent::setUp();

        // Create a (mock) PDF document
        $this->pdfMock = $this->getMockBuilder('Pdflax\Contracts\PdfDocumentInterface')->getMock();

        // (constructing a view will call getStylesheet)
        $this->pdfMock->expects($this->atLeastOnce())->method('getStylesheet')->willReturn([
            'body' => [
                // These will be translated to local values withing the view
                'font-size'   => 10,
                'border-size' => 2
            ]
        ]);

        // Create a view in that document, at 10,20.
        $this->pdfView = new PdfView($this->pdfMock, 10, 20, 25, 50);

        // Within the view, use 100x100 as coordinate system.
        // This will correspond to the witdh (25) and height (50) of the pdf (see above).
        $this->pdfView->setReferenceSize(100, 100);

    }

    /**
     * @test
     */
    public function it_knows_its_position_and_size_within_its_parent()
    {
        $this->assertEquals(10, $this->pdfView->getX());
        $this->assertEquals(20, $this->pdfView->getY());
        $this->assertEquals(25, $this->pdfView->getWidth());
        $this->assertEquals(50, $this->pdfView->getHeight());
    }

    /**
     * @test
     */
    public function it_accepts_a_size_in_percentage()
    {

        // Let the pdf tell the view that its size is 100x200
        $this->pdfMock->expects($this->atLeastOnce())->method('getWidth')->willReturn(100);
        $this->pdfMock->expects($this->atLeastOnce())->method('getHeight')->willReturn(200);

        // (constructing a view will call getStylesheet)
        // We need at(2), not at(1) because all method call count, not just getStylesheet() (phpunit issue #674)
        //$this->pdfMock->expects($this->at(2))->method('getStylesheet')->willReturn([]);

        // Create a view in that document, at 10,20, 50% or parent width/height.
        $this->pdfView = new PdfView($this->pdfMock, 10, 20, '50%', '50%');

    }

    /**
     * @test
     */
    public function it_sets_the_cursor_x()
    {

        $this->pdfMock
            ->expects($this->exactly(2))
            ->method('setCursorX')
            ->withConsecutive(
                [10],
                [35]
            );

        // Far left (10 in pdf)
        $this->pdfView->setCursorX(0);

        // Far right (35 in pdf)
        $self = $this->pdfView->setCursorX(100);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_sets_the_cursor_y()
    {

        $this->pdfMock
            ->expects($this->exactly(2))
            ->method('setCursorY')
            ->withConsecutive(
                [20],
                [70]
            );

        // Far left (20 in pdf)
        $this->pdfView->setCursorY(0);

        // Far right (70 in pdf)
        $self = $this->pdfView->setCursorY(100);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_sets_the_cursor_x_and_y()
    {

        $this->pdfMock
            ->expects($this->exactly(2))
            ->method('setCursorXY')
            ->withConsecutive(
                [10, 20],
                [35, 70]
            );

        // Top left (10, 20 in pdf)
        $this->pdfView->setCursorXY(0, 0);

        // Bottom right (35, 70 in pdf)
        $self = $this->pdfView->setCursorXY(100, 100);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_gets_the_cursor_x()
    {

        $this->pdfMock
            ->expects($this->exactly(2))
            ->method('getCursorX')
            ->willReturnOnConsecutiveCalls(
                10,
                35
            );

        // Far left (10 in pdf)
        $this->assertEquals(0, $this->pdfView->getCursorX());

        // Far right (35 in pdf)
        $this->assertEquals(100, $this->pdfView->getCursorX());

    }

    /**
     * @test
     */
    public function it_gets_the_cursor_y()
    {

        $this->pdfMock
            ->expects($this->exactly(2))
            ->method('getCursorY')
            ->willReturnOnConsecutiveCalls(
                20,
                70
            );

        // Far left (10 in pdf)
        $this->assertEquals(0, $this->pdfView->getCursorY());

        // Far right (35 in pdf)
        $this->assertEquals(100, $this->pdfView->getCursorY());

    }

    /**
     * @test
     */
    public function it_draws_a_rectangle()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('rectangle')
            ->with(10, 20, 25, 50);

        $self = $this->pdfView->rectangle(0, 0, 100, 100);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_draws_a_line()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('line')
            ->with(10, 20, 35, 70);

        $self = $this->pdfView->line(0, 0, 100, 100);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_draws_an_image()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('image')
            ->with('FILE', 10, 20, 25, 50, 'TYPE', 'LINK');

        $self = $this->pdfView->image('FILE', 0, 0, 100, 100, 'TYPE', 'LINK');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_sets_the_font_and_scales_it_horizontally()
    {

        // Font size 10 = 10% of 100 reference width
        // Should be 10% of parent width (25), so 2.5
        $this->pdfMock
            ->expects($this->once())
            ->method('setFont')
            ->with('FILE', 'STYLE', 2.5);

        $self = $this->pdfView->setFont('FILE', 'STYLE', 10);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_writes_a_text_and_scales_it_vertically()
    {

        // Font size 10 = 10% of 100 reference height
        // Should be 10% of parent height (50), so 5
        $this->pdfMock
            ->expects($this->once())
            ->method('write')
            ->with(5, 'TEXT', 'LINK');

        $self = $this->pdfView->write(10, 'TEXT', 'LINK');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_set_the_draw_color()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('setDrawColor')
            ->with(1, 2, 3);

        $self = $this->pdfView->setDrawColor(1, 2, 3);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_set_the_fill_color()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('setFillColor')
            ->with(1, 2, 3);

        $self = $this->pdfView->setFillColor(1, 2, 3);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_set_the_text_color()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('setTextColor')
            ->with(1, 2, 3);

        $self = $this->pdfView->setTextColor(1, 2, 3);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_draw_a_euro_symbol()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('euro')
            ->with(5);

        $self = $this->pdfView->euro(5);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_adds_a_page()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('addPage');

        $self = $this->pdfView->addPage();

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_adds_a_newLine()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('newLine')
            ->with(2, 'OPTIONS');

        $self = $this->pdfView->newLine(2, 'OPTIONS');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_saves_to_file()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('save')
            ->with('FILENAME');

        $self = $this->pdfView->save('FILENAME');

        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_returns_as_string()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('getPdfContent')
            ->willReturn('CONTENT');

        $this->assertSame('CONTENT', $this->pdfView->getPdfContent());
    }

    /**
     * @test
     */
    public function it_returns_the_underlying_raw_implementation()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('raw')
            ->willReturn('RAW_INSTANCE');

        $this->assertSame('RAW_INSTANCE', $this->pdfView->raw());
    }

    // ------------------ Stylesheets

    /**
     * @test
     */
    public function it_uses_and_translates_parent_stylesheet()
    {
        $this->assertEquals([
            'body' => [
                // These should have been translated to local values
                'font-size'   => 20,
                'border-size' => 4
            ]
        ], $this->pdfView->getStylesheet());

    }

    // ------------------ Margins (PdfMarginInterface, PdfMarginTrait)

    /**
     * @test
     */
    public function it_sets_and_returns_a_left_margin()
    {
        $self = $this->pdfView->setLeftMargin(12.5);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);

        $this->assertEquals($this->pdfView->getLeftMargin(), 12.5);
    }

    /**
     * @test
     */
    public function it_sets_and_returns_a_right_margin()
    {
        $self = $this->pdfView->setRightMargin(12.5);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);

        $this->assertEquals($this->pdfView->getRightMargin(), 12.5);
    }

}
