<?php

namespace Helix\Tests\Tokenizers;

use Helix\Tokenizers\Fragment;
use PHPUnit\Framework\TestCase;

/**
 * @group Tokenizers
 * @covers \Helix\Tokenizers\Fragment
 */
class FragmentTest extends TestCase
{
    /**
     * @var \Helix\Tokenizers\Fragment
     */
    protected $tokenizer;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->tokenizer = new Fragment(4);
    }

    /**
     * @test
     */
    public function tokenize() : void
    {
        $expected = ['CGGT', 'TCAG', 'TAAT'];

        $tokens = $this->tokenizer->tokenize('CGGTTCAGCANGTAAT');

        $this->assertEquals($expected, iterator_to_array($tokens));
    }
}
