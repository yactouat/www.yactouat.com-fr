<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\PresentationParagraphModel;

/**
 * typed collection of personal presentation paragraph model instances
*/
final class PresentationParagraphCollection extends Collection
{
    /**
     * gets an iterator to loop over personal presentation paragraph instances
     *
     * this iterator will filter out what is not a personal presentation paragraph
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
            // here we tell our filter to only accept personal presentation paragraph instances
            public function accept(): bool
            {
                return get_class($this->current()) === PresentationParagraphModel::class;
            }
        };
    }

    /**
     * adds only presentation section instances to this instance list
     *
     * @param PresentationParagraphModel $presSection
     * @return void
     */
    public function addItem(PresentationParagraphModel $presSection): void
    {
        $this->list[] = $presSection;
    }

    /**
     * the method to get a presentation section
     *
     * @param integer $position
     * @return PresentationParagraphModel|null
     */
    public function getItem(int $position): ?PresentationParagraphModel
    {
        return $this->list[$position] ?? null;
    }
}
