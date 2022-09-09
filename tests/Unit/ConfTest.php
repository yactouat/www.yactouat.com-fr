<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Conf;
use PHPUnit\Framework\TestCase;

final class ConfTest extends TestCase {

    // resetting the PHP conf after tests ran
    protected function tearDown(): void
    {
        ini_set("log_errors", 1);
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        ini_set("error_reporting", E_ALL);
    }

    public function testCheckDevConf() {
        $this->assertTrue(Conf::checkDevConf());
    }

    public function testCheckDevConfWithIncorrectConfReturnsFalse() {
        ini_set("display_errors", 0);
        $this->assertFalse(Conf::checkDevConf());
    }

    public function testCheckSharedConf() {
        $this->assertTrue(Conf::checkSharedConf());
    }

    public function testCheckSharedConfWithIncorrectConfReturnsFalse() {
        ini_set("error_reporting", E_ERROR);
        $this->assertFalse(Conf::checkSharedConf());
    }

}

