<?php

namespace Helix\Tokenizers;

use Helix\Exceptions\InvalidArgumentException;
use Generator;

/**
 * Fragment
 *
 * Generates a non-overlapping fragment of length n from a sequence.
 *
 * !!! note
 *     Fragments that contain invalid bases will not be generated.
 *
 * @category    Bioinformatics
 * @package     Scienide/Helix
 * @author      Andrew DalPino
 */
class Fragment implements Tokenizer
{
    /**
     * The length of tokenized sequences.
     *
     * @var int
     */
    protected int $n;

    /**
     * The number of fragments that were dropped due to invalid bases.
     *
     * @var int
     */
    protected int $dropped = 0;

    /**
     * @param int $n
     * @throws \Helix\Exceptions\InvalidArgumentException
     */
    public function __construct(int $n)
    {
        if ($n < 1) {
            throw new InvalidArgumentException('N must be'
                . " greater than 1, $n given.");
        }

        $this->n = $n;
    }

    /**
     * Return the number of k-mers that were dropped due to invalid bases.
     *
     * @return int
     */
    public function dropped() : int
    {
        return $this->dropped;
    }

    /**
     * Return an iterator for the tokens in a sequence.
     *
     * @param string $sequence
     * @return \Generator<string>
     */
    public function tokenize(string $sequence) : Generator
    {
        $p = strlen($sequence) - $this->n;

        for ($i = 0; $i <= $p; $i += $this->n) {
            $token = substr($sequence, $i, $this->n);

            if (preg_match('/[^ACTG]/', $token)) {
                ++$this->dropped;

                continue;
            }

            yield $token;
        }
    }
}
