<?php

use Relaxsd\Pdflax\Color;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{

    /**
     * @test
     */
    public function it_has_constants_for_common_colors()
    {
        $this->assertEquals('red', Color::RED);
        $this->assertEquals('white', Color::WHITE);
        $this->assertEquals('black', Color::BLACK);
    }

    /**
     * @test
     */
    public function it_translates_colors_to_rgb()
    {
        $this->assertEquals([0, 0, 0], Color::toRGB(Color::BLACK));
        $this->assertEquals([255, 255, 255], Color::toRGB(Color::WHITE));
        $this->assertEquals([255, 0, 0], Color::toRGB(Color::RED));
    }

    /**
     * @test
     */
    public function it_translates_r_to_greyscale()
    {
        $this->assertEquals([0, 0, 0], Color::toRGB(0));
        $this->assertEquals([127, 127, 127], Color::toRGB(127));
        $this->assertEquals([255, 255, 255], Color::toRGB(255));
    }

    /**
     * @test
     */
    public function it_transates_rgb()
    {
        $this->assertEquals([0, 1, 2], Color::toRGB(0, 1, 2));
    }

    /**
     * @test
     */
    public function it_accepts_arrays()
    {
        $this->assertEquals([0, 0, 0], Color::toRGB([Color::BLACK]));
        $this->assertEquals([0, 0, 0], Color::toRGB([0]));
        $this->assertEquals([127, 127, 127], Color::toRGB([127]));
        $this->assertEquals([0, 1, 2], Color::toRGB([0, 1, 2]));
    }

    /**
     * @test
     */
    public function it_returns_null_for_unknown_colors()
    {
        $this->assertNull(Color::toRGB('(nonexistent)'));
        $this->assertNull(Color::toRGB(new StdClass()));
    }

}
