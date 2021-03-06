<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Contracts\CurrencyFormatterInterface;
use Relaxsd\Pdflax\Helpers\Converter;
use Relaxsd\Pdflax\PdfView;
use Relaxsd\Stylesheets\Attributes\FontSize;
use Relaxsd\Stylesheets\Attributes\LineHeight;
use Relaxsd\Stylesheets\Attributes\PageOrientation;
use Relaxsd\Stylesheets\Attributes\PageSize;
use Relaxsd\Stylesheets\Style;
use Relaxsd\Stylesheets\Stylesheet;

class PdfViewTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Relaxsd\Pdflax\PdfView
     */
    protected $pdfView;

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
        $this->pdfMock->expects($this->atLeastOnce())->method('getInnerWidth')->willReturn(100);
        $this->pdfMock->expects($this->atLeastOnce())->method('getInnerHeight')->willReturn(200);

        // Create a view in that document, at 10,20, 50% of parent width/height (50x100).
        $this->pdfView = new PdfView($this->pdfMock, 10, 20, '50%', '50%');
        $this->assertEquals(50, $this->pdfView->getWidth());
        $this->assertEquals(100, $this->pdfView->getHeight());
    }

    /**
     * @test
     */
    public function it_adjusts_styles_when_chaning_reference_size()
    {
        $this->pdfView->addStylesheet(new Stylesheet([
            'style' => [
                'font-size' => 10
            ]
        ]));
        $this->pdfView->setReferenceSize(200, 200, true);

        $expected = (new Stylesheet([
            'style' => [
                'font-size' => 20
            ]
        ]));
        $this->assertEquals($expected, $this->pdfView->getStylesheet());
    }

    /**
     * @test
     */
    public function it_sets_the_cursor_x()
    {

        $this->pdfMock
            ->expects($this->exactly(3))
            ->method('setCursorX')
            ->withConsecutive(
                [10],
                [35],
                [22.5]
            );

        // Far left (10 in pdf)
        $self = $this->pdfView->setCursorX(0);

        // Far right (35 in pdf)
        $this->pdfView->setCursorX(100);

        $this->pdfView->setCursorX('50%');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);

    }

    /**
     * @test
     */
    public function it_sets_the_cursor_y()
    {

        $this->pdfMock
            ->expects($this->exactly(3))
            ->method('setCursorY')
            ->withConsecutive(
                [20],
                [70],
                [45]
            );

        // Far left (20 in pdf)
        $self = $this->pdfView->setCursorY(0);

        // Far right (70 in pdf)
        $this->pdfView->setCursorY(100);

        // 50%
        $this->pdfView->setCursorY('50%');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_sets_the_cursor_x_and_y()
    {

        $this->pdfMock
            ->expects($this->exactly(3))
            ->method('setCursorXY')
            ->withConsecutive(
                [10, 20],
                [35, 70],
                [22.5, 45]
            );

        // Top left (10, 20 in pdf)
        $self = $this->pdfView->setCursorXY(0, 0);

        // Bottom right (35, 70 in pdf)
        $this->pdfView->setCursorXY(100, 100);

        // at 50%, 50%
        $this->pdfView->setCursorXY('50%', '50%');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_moved_the_cursor_horizontally()
    {

        $this->pdfMock
            ->expects($this->exactly(3))
            ->method('moveCursorX')
            ->withConsecutive(
                [0],
                [25],
                [12.5]
            );

        // Far left (10 in pdf)
        $self = $this->pdfView->moveCursorX(0);

        // Far right (35 in pdf)
        $this->pdfView->moveCursorX(100);

        $this->pdfView->moveCursorX('50%');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);

    }

    /**
     * @test
     */
    public function it_moved_the_cursor_vertically()
    {

        $this->pdfMock
            ->expects($this->exactly(3))
            ->method('moveCursorY')
            ->withConsecutive(
                [0],
                [50],
                [25]
            );

        // Far left (10 in pdf)
        $self = $this->pdfView->moveCursorY(0);

        // Far right (35 in pdf)
        $this->pdfView->moveCursorY(100);

        $this->pdfView->moveCursorY('50%');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);

    }

    /**
     * @test
     */
    public function it_gets_the_current_page()
    {

        $this->pdfMock
            ->expects($this->once())
            ->method('getPage')
            ->willReturn(10);

        $this->assertEquals(10, $this->pdfView->getPage());

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
    public function it_return_a_currency_formatter()
    {

        $options = [
            CurrencyFormatterInterface::OPTION_EURO_SYMBOL => 'EUR'
        ];

        $this->pdfMock
            ->expects($this->once())
            ->method('getCurrencyFormatter')
            ->with($options)
            ->willReturn('FORMATTER');

        $currencyFormatter = $this->pdfView->getCurrencyFormatter($options);

        $this->assertEquals('FORMATTER', $currencyFormatter);
    }

    /**
     * @test
     */
    public function it_adds_a_page()
    {

        $this->pdfMock
            ->expects($this->exactly(4))
            ->method('addPage')
            ->withConsecutive(
                [],
                [PageOrientation::LANDSCAPE],
                [null, PageSize::A4],
                [PageOrientation::PORTRAIT, [100, 200]]
            );

        $self = $this->pdfView->addPage();
        $this->pdfView->addPage(PageOrientation::LANDSCAPE);
        $this->pdfView->addPage(null, PageSize::A4);
        $this->pdfView->addPage(PageOrientation::PORTRAIT, [100, 200]);

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

    /**
     * @test
     */
    public function it_draws_a_cell()
    {
        $this->pdfMock
            ->expects($this->once())
            ->method('cell')
            ->with(10 + 5 * 25 / 100, 20 + 7 * 50 / 100, 10 * 25 / 100, 20 * 50 / 100, 'text', new Style(['font-size' => 32]), [ 'option' => 'option-value' ]);

        $self = $this->pdfView->cell(5, 7, 10, 20, 'text', ['font-size' => 8], [ 'option' => 'option-value' ]);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_writes_text()
    {
        $this->pdfMock
            ->expects($this->once())
            ->method('text')
            ->with(10, 'text', new Style(['font-size' => 32]), ['option' => 'option-value']);

        $self = $this->pdfView->text(20, 'text', ['font-size' => 8], ['option' => 'option-value']);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    // ------------------ Fonts

    /**
     * @test
     */
    public function it_sets_a_font_path()
    {
        $this->pdfMock
            ->expects($this->once())
            ->method('setFontPath')
            ->with('path');

        $self = $this->pdfView->setFontPath('path');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
    }

    /**
     * @test
     */
    public function it_registers_a_font()
    {
        $this->pdfMock
            ->expects($this->once())
            ->method('registerFont')
            ->with('family', 'style', 'filename');

        $self = $this->pdfView->registerFont('family', 'style', 'filename');

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);
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

    /**
     * @test
     */
    public function it_sets_and_returns_a_top_margin()
    {
        $self = $this->pdfView->setTopMargin(12.5);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);

        $this->assertEquals($this->pdfView->getTopMargin(), 12.5);
    }

    /**
     * @test
     */
    public function it_sets_and_returns_a_bottom_margin()
    {
        $self = $this->pdfView->setBottomMargin(12.5);

        // Assert fluent interface
        $this->assertSame($this->pdfView, $self);

        $this->assertEquals($this->pdfView->getBottomMargin(), 12.5);
    }

    // ------------------ DOM (PdfDOMInterface)

    /**
     * @test
     */
    public function it_writes_a_paragraph()
    {

        $lineHeightMm = 0.5 /* vertical view ratio */ * 1.2 * LineHeight::DEFAULT_VALUE * Converter::points_to_mm(FontSize::DEFAULT_VALUE);

        $this->pdfMock->expects($this->exactly(2))->method('cell')->withConsecutive(
            [10.0, 50.0, 25.0, $lineHeightMm, 'text', new Style()],
            [10.0, 50.0, 25.0, $lineHeightMm, 'text', new Style(['name' => 'value'])]
        );

        // With mandatory params only
        $self = $this->pdfView->p('text');
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

        // With new style
        $self = $this->pdfView->p('text', ['name' => 'value']);
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface
    }

    /**
     * @test
     */
    public function it_writes_a_heading_1()
    {

        $lineHeightMm = 0.5 /* vertical view ratio */ * 1.2 * LineHeight::DEFAULT_VALUE * Converter::points_to_mm(FontSize::DEFAULT_VALUE);

        $this->pdfMock->expects($this->exactly(2))->method('cell')->withConsecutive(
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style()],
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style(['name' => 'value'])]
        );

        // With mandatory params only
        $self = $this->pdfView->h1('caption');
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

        // With new style
        $self = $this->pdfView->h1('caption', ['name' => 'value']);
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface
    }

    /**
     * @test
     */
    public function it_writes_a_heading_2()
    {

        $lineHeightMm = 0.5 /* vertical view ratio */ * 1.2 * LineHeight::DEFAULT_VALUE * Converter::points_to_mm(FontSize::DEFAULT_VALUE);

        $this->pdfMock->expects($this->exactly(2))->method('cell')->withConsecutive(
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style()],
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style(['name' => 'value'])]
        );

        // With mandatory params only
        $self = $this->pdfView->h2('caption');
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

        // With new style
        $self = $this->pdfView->h2('caption', ['name' => 'value']);
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

    }

    /**
     * @test
     */
    public function it_writes_a_heading_3()
    {

        $lineHeightMm = 0.5 /* vertical view ratio */ * 1.2 * LineHeight::DEFAULT_VALUE * Converter::points_to_mm(FontSize::DEFAULT_VALUE);

        $this->pdfMock->expects($this->exactly(2))->method('cell')->withConsecutive(
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style()],
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style(['name' => 'value'])]
        );

        // With mandatory params only
        $self = $this->pdfView->h3('caption');
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

        // With new style
        $self = $this->pdfView->h3('caption', ['name' => 'value']);
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

    }

    /**
     * @test
     */
    public function it_writes_a_heading_4()
    {

        $lineHeightMm = 0.5 /* vertical view ratio */ * 1.2 * LineHeight::DEFAULT_VALUE * Converter::points_to_mm(FontSize::DEFAULT_VALUE);

        $this->pdfMock->expects($this->exactly(2))->method('cell')->withConsecutive(
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style()],
            [10.0, 50.0, 25.0, $lineHeightMm, 'caption', new Style(['name' => 'value'])]
        );

        // With mandatory params only
        $self = $this->pdfView->h4('caption');
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

        // With new style
        $self = $this->pdfView->h4('caption', ['name' => 'value']);
        $this->assertSame($this->pdfView, $self);         // Assert fluent interface

    }

}
