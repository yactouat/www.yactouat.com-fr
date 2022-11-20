<?php

declare(strict_types=1);

namespace App\Collections;

/**
 * typed collection of personal presentation model instances
*/
abstract class Collection implements \IteratorAggregate
{
    /**
     * holds the presentation sections within this instance
     *
     * @var \ArrayIterator
     */
    protected \ArrayIterator $list = [];


    /**
     * the method to get the actual size (or count) of our collection
     *
     * @return integer
     */
    public function getListSize(): int
    {
        return count($this->list);
    }
}
