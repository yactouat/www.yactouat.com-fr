<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\PresentationSectionModel;

/**
 * typed collection of personal presentation section model instances
*/
final class PresentationSectionCollection extends Collection
{
    /**
     * gets an iterator to loop over personal presentation section instances
     *
     * this iterator will filter out what is not a personal presentation section
     *
     * @return \FilterIterator
     */
    public function getIterator(): \FilterIterator
    {
        return new class ($this->list) extends \FilterIterator {
            public function __construct(\ArrayIterator $iterator)
            {
                parent::__construct($iterator);
            }
            // here we tell our filter to only accept personal presentation section instances
            public function accept(): bool
            {
                return get_class($this->current()) === PresentationSectionModel::class;
            }
        };
    }

    /**
     * adds only presentation section instances to this instance list
     *
     * @param PresentationSectionModel $presSection
     * @return void
     */
    public function addItem(PresentationSectionModel $presSection): void
    {
        $this->list[] = $presSection;
    }

    /**
     * the method to get a presentation section
     *
     * @param integer $position
     * @return PresentationSectionModel|null
     */
    public function getItem(int $position): ?PresentationSectionModel
    {
        return $this->list[$position] ?? null;
    }
}
