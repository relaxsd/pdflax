<?php

use Pdflax\Helpers\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{

    public function testItReturnAnArrayIfGivenNoParameters()
    {
        $this->assertEquals([], Arr::mergeRecursiveConfig());
    }

    public function testItMergesArrayWithNumericKeys()
    {
        $this->assertEquals(
            [
                'one',
                'two'
            ],
            Arr::mergeRecursiveConfig([
                'one'
            ], [
                'two'
            ]));
    }

    public function testItMergesArraysRecursively()
    {
        $this->assertEquals(
            [
                'one' => ['a', 'b'],
            ],
            Arr::mergeRecursiveConfig([
                'one' => ['a']
            ], [
                'one' => ['b']
            ]));
    }

}
