<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Constants;
use App\Models\PresentationParagraphModel;
use LengthException;
use PHPUnit\Framework\TestCase;

final class PresentationParagraphModelTest extends TestCase
{
    public function testConstructWithEmptyParagraphThrowsLengthException()
    {
        $this->expectException(LengthException::class);
        $this->expectExceptionMessage(Constants::EXP_PEMPTY);
        $actual = new PresentationParagraphModel('');
    }

    public function testGetParagraphReturnsEscapedParagraph()
    {
        $someCode = '<script>alert("I am bad")</script>';
        $expected = htmlspecialchars($someCode);
        $instance = new PresentationParagraphModel($someCode);
        $actual = $instance->getParagraph();
        $this->assertEquals($expected, $actual);
    }
}
