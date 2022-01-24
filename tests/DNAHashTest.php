<?php

namespace Helix\Tests;

use Helix\DNAHash;
use PHPUnit\Framework\TestCase;

/**
 * @group Base
 * @covers \Helix\DNAHash
 */
class DNAHashTest extends TestCase
{
    private const BASES = [
        'A', 'C', 'G', 'T',
    ];

    private const NUM_SEQUENCES = 10000;

    private const RANDOM_SEED = 0;

    /**
     * @var \Helix\DNAHash
     */
    protected $hashTable;

    /**
     * Generate a read of length k.
     *
     * @param int $k
     * @return string
     */
    private static function generateRead(int $k) : string
    {
        $sequence = '';

        for ($i = 0; $i < $k; ++$i) {
            $sequence .= self::BASES[rand(0, 3)];
        }

        return $sequence;
    }

    /**
     * @before
     */
    protected function setUp() : void
    {
        $hashTable = new DNAHash(0.001);

        srand(self::RANDOM_SEED);

        for ($i = 0; $i < self::NUM_SEQUENCES; ++$i) {
            $hashTable->increment(self::generateRead(5));
        }

        $this->hashTable = $hashTable;
    }

    /**
     * @test
     */
    public function numSingletons() : void
    {
        $this->assertEquals(1, $this->hashTable->numSingletons());
    }

    /**
     * @test
     */
    public function numNonSingletons() : void
    {
        $this->assertEquals(9999, $this->hashTable->numNonSingletons());
    }

    /**
     * @test
     */
    public function numSequences() : void
    {
        $this->assertEquals(self::NUM_SEQUENCES, $this->hashTable->numSequences());
    }

    /**
     * @test
     */
    public function numUniqueSequences() : void
    {
        $this->assertEquals(1024, $this->hashTable->numUniqueSequences());
    }

    /**
     * @test
     */
    public function max() : void
    {
        $this->assertEquals(21, $this->hashTable->max());
    }

    /**
     * @test
     */
    public function argmax() : void
    {
        $this->assertEquals('GGTCG', $this->hashTable->argmax());
    }

    /**
     * @test
     */
    public function top() : void
    {
        $expected = [
            'GGTCG' => 21,
            'CGTGG' => 20,
            'TCCCG' => 19,
        ];

        $top3 = $this->hashTable->top(3);

        $this->assertEquals($expected, iterator_to_array($top3));
    }

    /**
     * @test
     */
    public function histogram() : void
    {
        $expected = [
            5 => 78,
            10 => 550,
            15 => 362,
            20 => 33,
            25 => 1,
        ];

        $this->assertEquals($expected, $this->hashTable->histogram(5));
    }

    /**
     * @test
     */
    public function offsetSet() : void
    {
        $this->hashTable['GAATA'] = 42;

        $this->assertEquals(42, $this->hashTable['GAATA']);
    }

    /**
     * @test
     */
    public function offsetExists() : void
    {
        $this->assertTrue(isset($this->hashTable['AATTA']));

        $this->assertFalse(isset($this->hashTable['ACTNG']));
    }

    /**
     * @test
     */
    public function offsetGet() : void
    {
        $this->assertEquals(18, $this->hashTable['AATTA']);
    }

    /**
     * @test
     */
    public function countable() : void
    {
        $this->assertEquals(self::NUM_SEQUENCES, count($this->hashTable));
    }
}
