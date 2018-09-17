<?php

use Pdflax\Style\Styles;
use Pdflax\Style\Stylesheet;
use PHPUnit\Framework\TestCase;

class StylesTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Pdflax\Style\Styles
     */
    protected $styles;

    /**
     * A Stylesheet mock object
     *
     * @var \Pdflax\Style\Stylesheet|PHPUnit_Framework_MockObject_MockObject
     */
    protected $stylesheetMock;

    protected function setUp()
    {
        parent::setUp();

        $this->stylesheetMock = $this->getMockBuilder('Pdflax\Style\Stylesheet')->getMock();
        $this->styles         = new Styles($this->stylesheetMock);
    }

    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $this->assertSame($this->stylesheetMock, $this->styles->getStylesheet());
    }

    /**
     * @test
     */
    public function it_merges_styles()
    {
        $this->styles
            ->add('NAME1', 'VALUE1')
            ->add('NAME2', 'VALUE2');

        $newStyles = (new Styles(null))
            ->add('NAME2', 'NEW_VALUE2')
            ->add('NAME3', 'VALUE3');

        $self = $this->styles->mergeStyles($newStyles);

        $this->assertSame($this->styles, $self);
        $this->assertEquals('VALUE1', $this->styles->getValue('NAME1'));
        $this->assertEquals('NEW_VALUE2', $this->styles->getValue('NAME2'));
        $this->assertEquals('VALUE3', $this->styles->getValue('NAME3'));

    }

    /**
     * @test
     */
    public function it_stores_and_retreives_values()
    {
        $self = $this->styles->add('NAME', 'VALUE');
        $this->assertEquals('VALUE', $this->styles->getValue('NAME'));
        $this->assertSame($this->styles, $self);
    }

    /**
     * @test
     */
    public function it_overwrites_existing_values()
    {
        $this->styles
            ->add('NAME', 'VALUE')
            ->add('NAME', 'NEW_VALUE');

        $this->assertEquals('NEW_VALUE', $this->styles->getValue('NAME'));
    }

    /**
     * @test
     */
    public function it_returns_null_for_nonexistent_values()
    {
        $this->assertNull($this->styles->getValue('(nonexistent)'));
    }

    /**
     * @test
     */
    public function it_returns_all_values()
    {
        $this->styles
            ->add('NAME1', 'VALUE1')
            ->add('NAME2', 'VALUE2');

        $this->assertEquals([
            'NAME1' => 'VALUE1',
            'NAME2' => 'VALUE2',
        ], $this->styles->getValues(false));

    }

    /**
     * @test
     */
    public function it_returns_all_values_with_inherited()
    {
        // Mock some other Styles objects
        $anchestorStyles1 = (new Styles($this->stylesheetMock))
            ->add('border', 10)
            ->add('color', 'black');

        $anchestorStyles2 = (new Styles($this->stylesheetMock))
            ->add('color', 'white')
            ->add('underline', false);

        // Expect our stylesheet to be asked for the 'ANCESTOR' Styles. Return our mock
        $this->stylesheetMock
            ->expects($this->exactly(2))
            ->method('getStyles')
            ->withConsecutive(['ANCESTOR1'], ['ANCESTOR2'])
            ->willReturnOnConsecutiveCalls($anchestorStyles1, $anchestorStyles2);

        // Set our test subject to be a descendant of 'ANCHESTOR'
        $this->styles
            ->extendsFrom('ANCESTOR1', 'ANCESTOR2')
            ->add('underline', true);

        // Ask for all values. This->styles should have precedence over anchestor 2, over anchestor 1.
        $this->assertEquals([
            'border'    => 10,      // From anchestor 1
            'color'     => 'white', // From anchestor 2 (not 1)
            'underline' => true     // From our styles object (not anchestor 2)
        ], $this->styles->getValues());

    }

    /**
     * @test
     */
    public function it_returns_a_value_from_an_anchestor()
    {
        // Mock a second Styles object
        $anchestorStyles = (new Styles($this->stylesheetMock))->add('border', 10);

        // Expect our stylesheet to be asked for the 'ANCESTOR' Styles. Return our mock
        $this->stylesheetMock
            ->expects($this->exactly(1))
            ->method('getStyles')
            ->with('ANCESTOR')
            ->willReturn($anchestorStyles);

        // Set our test subject to be a descendant of 'ANCHESTOR'
        $this->styles->extendsFrom('ANCESTOR');

        // Ask for the 'border' style. It should be retrieve from 'ANCHESTOR' via the stylesheet
        $this->assertEquals(10, $this->styles->getValue('border'));
    }

    /**
     * @test
     */
    public function it_stores_and_retreives_a_stylesheet()
    {
        $newStylesheet = new Stylesheet();

        $self = $this->styles->setStylesheet($newStylesheet);
        $this->assertSame($newStylesheet, $this->styles->getStylesheet());
        $this->assertSame($this->styles, $self);
    }

    /**
     * @test
     */
    public function it_extends_from_one_or_more_ancestors()
    {
        $this->assertFalse($this->styles->isDescendantOf('a'));

        // Try one argument
        $self = $this->styles->extendsFrom('a');
        $this->assertTrue($this->styles->isDescendantOf('a'));
        $this->assertSame($this->styles, $self);

        // Try two arguments
        $self = $this->styles->extendsFrom('b', 'c', 'd');
        $this->assertTrue($this->styles->isDescendantOf('a'));
        $this->assertTrue($this->styles->isDescendantOf('b'));
        $this->assertTrue($this->styles->isDescendantOf('c'));
        $this->assertTrue($this->styles->isDescendantOf('d'));
        $this->assertSame($this->styles, $self);

        // Try one array argument
        $self = $this->styles->extendsFrom(['e', 'f']);
        $this->assertTrue($this->styles->isDescendantOf('a'));
        $this->assertTrue($this->styles->isDescendantOf('b'));
        $this->assertTrue($this->styles->isDescendantOf('c'));
        $this->assertTrue($this->styles->isDescendantOf('d'));
        $this->assertTrue($this->styles->isDescendantOf('e'));
        $this->assertTrue($this->styles->isDescendantOf('f'));
        $this->assertSame($this->styles, $self);
    }

    /**
     * @test
     */
    public function it_can_clone()
    {
        // Add a random style to out Styles
        $this->styles->add('NAME', 'VALUE');

        $newStylesheet = new Stylesheet();

        // Clone the Styles object, parenting to stylesheet 'NEW'
        $copy = $this->styles->copy($newStylesheet);
        $this->assertNotSame($this->styles, $copy);
        $this->assertSame($newStylesheet, $copy->getStylesheet());

        // Check that the copy has the same style 'Name' => 'VALUE'
        $this->assertEquals('VALUE', $copy->getValue('NAME'));
    }

    /**
     * @test
     */
    public function it_scales()
    {
        $this->styles
            ->add('border-size', 10)
            ->add('some-number', 20)
            ->add('size', 3);

        $self = $this->styles->scale(2.5);
        $this->assertSame($this->styles, $self);

        $this->assertEquals([
            'border-size'  => 25,
            'some-number' => 20, // unchanged
            'size'         => 7.5
        ], $this->styles->getValues());

    }
}
