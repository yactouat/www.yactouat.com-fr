<?php

declare(strict_types=1);

namespace App\Services;

/**
 * interface that sets the contracts for objects responsible for providing yactouat's personal introduction
 */
interface PersonalIntroServiceInterface
{
    /**
     * gets a collection of personal introduction sections as kv arrays
     *
     * @return array
     */
    public function getSections(): array;

    /**
     * sets a collection of personal introduction sections as kv arrays
     *
     * @return void
     */
    public function setSections(array $sections): void;
}
