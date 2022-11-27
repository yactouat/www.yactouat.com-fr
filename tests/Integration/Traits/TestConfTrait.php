<?php


declare(strict_types=1);

namespace Tests\Integration\Traits;

use App\Constants;

trait TestConfTrait
{
    protected function setTestConf()
    {
        $_ENV[Constants::APP_ENV] = Constants::DEV_ENV;
        ini_set("log_errors", 1);
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        ini_set("error_reporting", E_ALL);
    }
}
