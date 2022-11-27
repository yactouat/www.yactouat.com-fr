<?php

declare(strict_types=1);

namespace Tests\Integration\Core;

use App\Core\Kernel;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Traits\TestRequestTrait;

final class KernelTest extends TestCase
{
    use TestRequestTrait;

    protected function setUp(): void
    {
        $this->resetTestRequest();
    }

    public function testParseClientRequestSetsRequestObjectWithUriSet()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $expected = '/';
        $actual = (new Kernel())->parseClientRequest()->getRequest()->getUri();
        $this->assertEquals($expected, $actual);
    }

    // TODO test with `mentions-legales` route
}
