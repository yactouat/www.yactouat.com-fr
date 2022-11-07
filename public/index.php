<?php

// defining root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\WebApp;

// sending response to client request
(new WebApp($rootDir))->sendResponse();