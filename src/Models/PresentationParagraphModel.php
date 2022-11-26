<?php

declare(strict_types=1);

namespace App\Models;

use App\Constants;
use LengthException;

/**
 * represents a personal presentation paragraph model
 */
final class PresentationParagraphModel
{
    /**
     * sets an instance of a personal presentation paragraph
     *
     * TODO test for escaped values
     *
     * @param string $paragraph
     */
    public function __construct(private string $_paragraph)
    {
        if (empty($this->_paragraph)) {
            throw new LengthException(Constants::EXP_PEMPTY);
        }
    }

    /**
     * returns the paragraph of this instance
     *
     * @return string
     */
    public function getParagraph(): string
    {
        return htmlspecialchars($this->_paragraph);
    }
}
