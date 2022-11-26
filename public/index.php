<?php

// defining root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\Constants;
use App\Exceptions\Error\ConfKOException;
use App\WebApp;

// sending response to client request
try {
    (new WebApp())->setConf($rootDir)->sendResponse();
} catch (ConfKOException $cke) {
    // TODO test http code + server error page
    http_response_code(500);
    echo Constants::ERR_EXP_CONFKO;
}