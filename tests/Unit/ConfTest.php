<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Conf;
use App\Constants;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

final class ConfTest extends TestCase
{
    // resetting the PHP conf and the env after tests ran
    protected function tearDown(): void
    {
        ini_set("log_errors", 1);
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        ini_set("error_reporting", E_ALL);
        \putenv(Constants::APP_ENV."=".Constants::DEV_ENV);
    }

    public function testCheckDevConf()
    {
        $this->assertTrue(Conf::checkDevConf());
    }

    public function testCheckDevConfWhenProdReturnsTrue()
    {
        \putenv(Constants::APP_ENV."=".Constants::PROD_ENV);
        ini_set("display_errors", 0);
        $this->assertTrue(Conf::checkDevConf());
    }

    public function testCheckDevConfWithIncorrectConfReturnsFalse()
    {
        ini_set("display_errors", 0);
        $this->assertFalse(Conf::checkDevConf());
    }

    public function testCheckSharedConf()
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
        $expected = getcwd();
        $conf = new Conf($expected);
        $actual = $conf->getRootDir();
        $this->assertSame($expected, $actual);
    }

    public function testConstructLoadsTwigEnv()
    {
        $conf = new Conf(getcwd());
        $this->assertInstanceOf(Environment::class, $conf->twig);
    }
}
