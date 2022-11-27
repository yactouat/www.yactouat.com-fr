<?php

declare(strict_types=1);

namespace App\Core\Http;

final class Header
{
    public function __construct(private string $_field, private string $_value)
    {
    }
}
