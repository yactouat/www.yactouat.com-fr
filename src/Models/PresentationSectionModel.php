<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\PresentationParagraphCollection;

/**
 * represents a personal presentation section model
 */
final class PresentationSectionModel
{
    /**
     * sets an instance of a personal presentation section
     *
     * TODO test for heading empty strings
     * TODO test for heading escaped values
     *
     * @param string $heading
     * @param array $paragraphs
     */
    public function __construct(private string $heading, private PresentationParagraphCollection $paragraphs)
    {
    }
}
