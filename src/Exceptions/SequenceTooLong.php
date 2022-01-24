<?php

namespace Helix\Exceptions;

class SequenceTooLong extends InvalidArgumentException
{
    /**
     * @param int $length
     * @param int $maxLength
     */
    public function __construct(int $length, int $maxLength)
    {
        parent::__construct("Sequence length must be less than $maxLength, $length given.");
    }
}
