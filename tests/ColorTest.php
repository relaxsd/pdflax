<?php

use Pdflax\Color;
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
    public function it_returns_null_for_unknown_colors()
    {
        $this->assertNull(Color::toRGB('(nonexistent)'));
    }

}
