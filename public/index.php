<?php

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\Conf;
use App\Constants;

if (!Conf::checkDevConf() || !Conf::checkSharedConf()) {
    http_response_code(500);
    die("conf KO");
}

echo "it works in env ".getenv(Constants::APP_ENV, true)."\n";
echo "cloud build addition";