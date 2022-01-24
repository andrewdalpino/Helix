<?php

namespace Helix\Benchmarks;

use Helix\DNAHash;

/**
 * @BeforeMethods({"setUp"})
 */
class HelixBench
{
    private const BASES = [
        'A', 'C', 'G', 'T',
    ];

    private const NUM_READS = 1000000;

    private const SEQUENCE_LENGTH = 25;

    /**
     * @var list<string>
     */
    protected $sequences;

    /**
     * @var \Helix\DNAHash
     */
    protected $hashTable;

    /**
     * Generate a k-mer of length k.
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

    public function setUp() : void
    {
        $sequences = [];

        for ($i = 0; $i < self::NUM_READS; ++$i) {
            $sequences[] = self::generateRead(self::SEQUENCE_LENGTH);
        }

        $this->sequences = $sequences;

        $this->hashTable = new DNAHash(0.01, 4);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function increment() : void
    {
        foreach ($this->sequences as $sequence) {
            $this->hashTable->increment($sequence);
        }
    }
}
