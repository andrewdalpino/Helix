<?php

namespace Helix\Tokenizers;

use Generator;

interface Tokenizer
{
    /**
     * Return an iterator for the tokens in a sequence.
     *
     * @param string $sequence
     * @return \Generator<string>
     */
    public function tokenize(string $sequence) : Generator;
}
