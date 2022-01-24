<?php

namespace Helix\Tests\Tokenizers;

use Helix\Tokenizers\Canonical;
use Helix\Tokenizers\Kmer;
use PHPUnit\Framework\TestCase;

/**
 * @group Tokenizers
 * @covers \Helix\Tokenizers\Canonical
 */
class CanonicalTest extends TestCase
{
    /**
     * @var \Helix\Tokenizers\Canonical
     */
    protected $tokenizer;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->tokenizer = new Canonical(new Kmer(6));
    }

    /**
     * @test
     */
    public function tokenize() : void
    {
        $expected = ['CGGTTC', 'GGTTCA', 'CTGAAC', 'GCTGAA', 'TCAGCA'];

        $tokens = $this->tokenizer->tokenize('CGGTTCAGCANG');

        $this->assertEquals($expected, iterator_to_array($tokens));
    }
}
