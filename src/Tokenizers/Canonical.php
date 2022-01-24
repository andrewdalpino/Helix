<?php

namespace Helix\Tokenizers;

use Helix\Exceptions\InvalidBase;
use Generator;

/**
 * Canonical
 *
 * @category    Bioinformatics
 * @package     Scienide/Helix
 * @author      Andrew DalPino
 */
class Canonical implements Tokenizer
{
    /**
     * The mapping of bases to their complimentary bases.
     *
     * @var string[]
     */
    protected const BASE_COMPLIMENT_MAP = [
        'A' => 'T',
        'T' => 'A',
        'C' => 'G',
        'G' => 'C',
    ];

    /**
     * The base tokenizer.
     *
     * @var \Helix\Tokenizers\Tokenizer
     */
    protected \Helix\Tokenizers\Tokenizer $base;

    /**
     * Return the reverse compliment of a sequence.
     *
     * @param string $sequence
     * @return string
     */
    protected static function reverseCompliment(string $sequence) : string
    {
        $reverseCompliment = '';

        for ($i = strlen($sequence) - 1; $i >= 0; --$i) {
            $base = $sequence[$i];

            if (!isset(self::BASE_COMPLIMENT_MAP[$base])) {
                throw new InvalidBase($base, self::BASE_COMPLIMENT_MAP);
            }

            $reverseCompliment .= self::BASE_COMPLIMENT_MAP[$base];
        }

        return $reverseCompliment;
    }

    /**
     * @param \Helix\Tokenizers\Tokenizer $base
     */
    public function __construct(Tokenizer $base)
    {
        $this->base = $base;
    }

    /**
     * Return an iterator for the tokens in a sequence.
     *
     * @param string $sequence
     * @return \Generator<string>
     */
    public function tokenize(string $sequence) : Generator
    {
        $tokens = $this->base->tokenize($sequence);

        foreach ($tokens as $token) {
            yield min($token, self::reverseCompliment($token));
        }
    }
}
