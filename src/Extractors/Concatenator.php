<?php

namespace Helix\Extractors;

use Generator;

/**
 * Concatenator
 *
 * Concatenates the output of multiple extractors.
 *
 * @category    Bioinformatics
 * @package     Scienide/Helix
 * @author      Andrew DalPino
 */
class Concatenator implements Extractor
{
    /**
     * A list of iterators.
     *
     * @var list<iterable<string>>
     */
    protected array $iterators;

    /**
     * @param iterable<string>[] $iterators
     */
    public function __construct(array $iterators)
    {
        $this->iterators = array_values($iterators);
    }

    /**
     * Return an iterator for the sequences in a dataset.
     *
     * @return \Generator<string>
     */
    public function getIterator() : Generator
    {
        foreach ($this->iterators as $iterator) {
            foreach ($iterator as $sequence) {
                yield $sequence;
            }
        }
    }
}
