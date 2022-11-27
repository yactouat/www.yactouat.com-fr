<?php


declare(strict_types=1);

namespace Tests\Integration\Traits;

trait TestRequestTrait
{
    protected function resetTestRequest()
    {
        $_SERVER = [];
    }
}
