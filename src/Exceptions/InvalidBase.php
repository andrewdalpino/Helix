<?php

namespace Helix\Exceptions;

class InvalidBase extends InvalidArgumentException
{
    /**
     * @param string $base
     * @param string[] $validBases
     */
    public function __construct(string $base, array $validBases)
    {
        $validBases = implode(', ', $validBases);

        parent::__construct("Sequence must only contain [$validBases] bases, '$base' given.");
    }
}
