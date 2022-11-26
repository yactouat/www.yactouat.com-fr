<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Constants;
use App\Core\WebApp;
use App\Exceptions\Error\ConfKOException;
use PHPUnit\Framework\TestCase;

final class WebAppTest extends TestCase
{
    use TestConfTrait;

    protected function setUp(): void
    {
        $this->setTestConf();
    }

    protected function tearDown(): void
    {
        $this->setTestConf();
    }

    public function testSendResponseWithBadConfThrowsConfKoException()
    {
        $actual = (new WebApp())->setConf(Constants::DOCKER_ROOT_DIR);
        ini_set("display_errors", 0);
        $this->expectException(ConfKOException::class);
        $this->expectExceptionMessage(Constants::ERR_EXP_CONFKO);
        $actual->sendResponse();
    }

    // TODO test dynamic insertion of presentation sections (happy + unhappy paths)
    // TODO test rendering with no presentation sections
    // TODO test with different fixtures
}
