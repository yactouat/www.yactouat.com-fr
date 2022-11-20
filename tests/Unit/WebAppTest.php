<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Constants;
use PHPUnit\Framework\TestCase;

final class WebAppTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV[Constants::APP_ENV] = Constants::DEV_ENV;
    }

    protected function tearDown(): void
    {
        unset($_ENV[Constants::APP_ENV]);
    }

    // TODO test dynamic insertion of presentation sections (happy + unhappy paths)
    // TODO test rendering with no presentation sections
    // TODO test with different fixtures
}
