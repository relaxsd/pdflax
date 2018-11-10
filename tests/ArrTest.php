<?php

use Relaxsd\Pdflax\Helpers\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{

    /**
     * @test
     */
    public function it_returns_an_array_if_given_no_parameters()
    {
        $this->assertEquals([], Arr::mergeRecursiveConfig());
    }

    /**
     * @test
     */
    public function it_merges_arrays_with_numeric_keys()
    {
        $this->assertEquals(
            [
                'one',
                'two'
            ],
            Arr::mergeRecursiveConfig(
                ['one'],
                ['two']
            )
        );
    }

    /**
     * @test
     */
    public function it_merges_arrays_recursively()
    {
        $this->assertEquals(
            [
                'one' => ['a', 'b'],
            ],
            Arr::mergeRecursiveConfig(
                ['one' => ['a']],
                ['one' => ['b']]
            )
        );
    }

}
