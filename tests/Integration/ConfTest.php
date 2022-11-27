<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Conf;
use App\Constants;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

final class ConfTest extends TestCase
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

    public function testCheckDevConf()
    {
        $this->assertTrue(Conf::checkDevConf());
    }

    public function testCheckDevConfWhenProdReturnsTrue()
    {
        $_ENV[Constants::APP_ENV] = Constants::PROD_ENV;
        ini_set("display_errors", 0);
        $this->assertTrue(Conf::checkDevConf());
    }

    public function testCheckDevConfWithIncorrectConfReturnsFalse()
    {
        ini_set("display_errors", 0);
        $this->assertFalse(Conf::checkDevConf());
    }

    public function testCheckSharedConfReturnsTrue()
    {
        $this->assertTrue(Conf::checkSharedConf());
    }

    public function testCheckSharedConfWithIncorrectConfReturnsFalse()
    {
        ini_set("error_reporting", E_ERROR);
        $this->assertFalse(Conf::checkSharedConf());
    }

    public function testConstructSetsRootDir()
    {
        $expected = Constants::DOCKER_ROOTDIR;
        $conf = new Conf($expected);
        $actual = $conf->getRootDir();
        $this->assertSame($expected, $actual);
    }

    public function testConstructLoadsTwigEnv()
    {
        $conf = new Conf(Constants::DOCKER_ROOTDIR);
        $this->assertInstanceOf(Environment::class, $conf->twig);
    }
}
