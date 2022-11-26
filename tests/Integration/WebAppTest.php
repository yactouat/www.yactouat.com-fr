<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Constants;
use App\Core\WebApp;
use App\Exceptions\Error\ConfKOException;
use PHPUnit\Framework\TestCase;
use Yactouat\Dev\StringsComparatorTrait;

final class WebAppTest extends TestCase
{
    use StringsComparatorTrait;
    use TestConfTrait;

    protected function setUp(): void
    {
        $this->setTestConf();
    }

    protected function tearDown(): void
    {
        $this->setTestConf();
    }

    public function testCheckConfWithBadConfThrowsConfKoException()
    {
        ini_set("display_errors", 0);
        $this->expectException(ConfKOException::class);
        $this->expectExceptionMessage(Constants::ERR_EXP_CONFKO);
        (new WebApp())->init(Constants::DOCKER_ROOTDIR)->checkConf();
    }

    public function testGetResponseBodyGetsIndexPage()
    {
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR)
            ->getResponseBody();
        $this->assertTrue($this->stringIsContainedInAnother('<title>yactouat.fr | accueil</title>', $actual));
        $this->assertTrue($this->stringIsContainedInAnother('<p class="header_about-text">', $actual));
        $this->assertTrue($this->stringIsContainedInAnother('<h2 class="main_container_heading">Qui je suis</h2>', $actual));
    }

    public function testGetResponseBodyWithBadConfGets500ErrorPage()
    {
        $expected = \file_get_contents(Constants::DOCKER_FIXTURESDIR . '500_error.html');
        ini_set("display_errors", 0);
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR)
            ->getResponseBody();
        $this->assertTrue($this->stringsHaveSameContent($expected, $actual));
    }

    public function testGetStatusCodeCodeSets200ErrorCode()
    {
        $expected = 200;
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR)
            ->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetStatusCodeCodeWithBadConfSets500ErrorCode()
    {
        $expected = 500;
        ini_set("display_errors", 0);
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR)
            ->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    // TODO test dynamic insertion of presentation sections (happy + unhappy paths)
    // TODO test rendering with no presentation sections
    // TODO test with different fixtures
}
