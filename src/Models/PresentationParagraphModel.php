<?php

declare(strict_types=1);

namespace App\Models;

/**
 * represents a personal presentation paragraph model
 */
final class PresentationParagraphModel
{
    /**
     * sets an instance of a personal presentation paragraph
     *
     * TODO test for empty strings
     * TODO test for escaped values
     *
     * @param string $paragraph
     */
    public function __construct(private string $paragraph)
    {
    }
}
