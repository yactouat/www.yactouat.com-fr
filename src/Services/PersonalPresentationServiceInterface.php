<?php

declare(strict_types=1);

namespace App\Services;

use App\Collections\PresentationSectionCollection;

/**
 * interface that sets the contracts for objects responsible for providing yactouat's personal presentation
 */
interface PersonalPresentationServiceInterface
{
    /**
     * gets a collection of personal presentation sections
     *
     * @return PresentationSectionCollection
     */
    public function getSections(): PresentationSectionCollection;
}
