<?php

use Pdflax\Style\Styles;
use Pdflax\Style\Stylesheet;
use PHPUnit\Framework\TestCase;

class StylesheetTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Pdflax\Style\Stylesheet
     */
    protected $stylesheet;

    protected function setUp()
    {
        parent::setUp();

        $this->stylesheet = new Stylesheet();
    }

    /**
     * @test
     */
    public function it_adds_and_appends_styles()
    {
        // Create a style that belongs to some other stylesheet
        $newStyles = new Styles();

        $self = $this->stylesheet->addStyles('p', $newStyles);
        $this->assertSame($this->stylesheet, $self);

        $storedStyles = $this->stylesheet->getStyles('p');
        $this->assertNotSame($newStyles, $storedStyles);
        $this->assertSame($this->stylesheet, $storedStyles->getStylesheet());
    }

    /**
     * @test
     */
    public function it_appends_to_existing_styles()
    {
        $newStyles1 = (new Styles())->add('border-size', 10);
        $newStyles2 = (new Styles())->add('font-size', 5);

        $this->stylesheet
            ->addStyles('p', $newStyles1)
            ->addStyles('p', $newStyles2);

        $this->assertEquals(['p'], $this->stylesheet->getElements());

        $storedStyles = $this->stylesheet->getStyles('p');
        $this->assertEquals(10, $storedStyles->getValue('border-size'));
        $this->assertEquals(5, $storedStyles->getValue('font-size'));
    }

    /**
     * @test
     */
    public function it_creates_an_empty_styles_object()
    {
        $self = $this->stylesheet->addStyles('p');
        $this->assertSame($this->stylesheet, $self);

        $this->assertEquals(['p'], $this->stylesheet->getElements());

        $storedStyles = $this->stylesheet->getStyles('p');
        $this->assertInstanceOf('Pdflax\Style\Styles', $storedStyles);
        $this->assertSame($this->stylesheet, $storedStyles->getStylesheet());
    }

    /**
     * @test
     */
    public function it_adds__and_appents_single_styles()
    {
        $self = $this->stylesheet->addStyle('ELEMENT', 'NAME1', 'VALUE1');

        $this->assertEquals(['ELEMENT'], $this->stylesheet->getElements());

        $styles = $this->stylesheet->getStyles('ELEMENT');
        $this->assertEquals('VALUE1', $styles->getValue('NAME1'));
        $this->assertSame($this->stylesheet, $self);

        $self = $this->stylesheet->addStyle('ELEMENT', 'NAME2', 'VALUE2');

        $this->assertEquals(['ELEMENT'], $this->stylesheet->getElements());

        $styles = $this->stylesheet->getStyles('ELEMENT');
        $this->assertEquals('VALUE1', $styles->getValue('NAME1'));
        $this->assertEquals('VALUE2', $styles->getValue('NAME2'));
        $this->assertSame($this->stylesheet, $self);
    }

    /**
     * @test
     */
    public function it_returns_null_if_styles_do_not_exist()
    {
        $this->assertNull($this->stylesheet->getStyles('(nonexistent)'));
    }

    /**
     * @test
     */
    public function it_merges_other_stylesheets()
    {
        $stylesheet2 = (new Stylesheet())->addStyle('p', 'background-color', 'yellow');
        $stylesheet3 = (new Stylesheet())->addStyle('p', 'font-size', 10);

        $self = $this->stylesheet->mergeStylesheets($stylesheet2, $stylesheet3);
        $this->assertSame($this->stylesheet, $self);

        $this->assertEquals(['p'], $this->stylesheet->getElements());

        $styles = $this->stylesheet->getStyles('p');
        $this->assertEquals('yellow', $styles->getValue('background-color'));
        $this->assertEquals(10, $styles->getValue('font-size'));
    }

    /**
     * @test
     */
    public function it_statically_merges_stylesheets()
    {
        $stylesheet1 = (new Stylesheet())->addStyle('p', 'background-color', 'yellow');
        $stylesheet2 = (new Stylesheet())->addStyle('p', 'font-size', 10);

        $mergeStylesheet = Stylesheet::merge($stylesheet1, $stylesheet2);

        $this->assertEquals(['p'], $mergeStylesheet->getElements());

        $styles = $mergeStylesheet->getStyles('p');
        $this->assertEquals('yellow', $styles->getValue('background-color'));
        $this->assertEquals(10, $styles->getValue('font-size'));
    }

}
