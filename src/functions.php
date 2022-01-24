<?php

namespace Helix
{
    use function array_search;
    use function max;

    /**
     * Compute the argmax of a set of values.
     *
     * @internal
     *
     * @param (int|float)[] $values
     * @return int|string|null
     */
    function argmax(array $values)
    {
        $index = array_search(max($values), $values);

        if ($index === false) {
            return null;
        }

        return $index;
    }
}
