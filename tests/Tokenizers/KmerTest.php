<?php

namespace Helix\Tests\Tokenizers;

use Helix\Tokenizers\Kmer;
use PHPUnit\Framework\TestCase;

/**
 * @group Tokenizers
 * @covers \Helix\Tokenizers\Kmer
 */
class KmerTest extends TestCase
{
    /**
     * @var \Helix\Tokenizers\Kmer
     */
    protected $tokenizer;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->tokenizer = new Kmer(6);
    }

    /**
     * @test
     */
    public function tokenize() : void
    {
        $expected = ['CGGTTC', 'GGTTCA', 'GTTCAG', 'TTCAGC', 'TCAGCA'];

        $tokens = $this->tokenizer->tokenize('CGGTTCAGCANG');

        $this->assertEquals($expected, iterator_to_array($tokens));
    }
}
