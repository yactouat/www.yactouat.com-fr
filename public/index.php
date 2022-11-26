<?php

// defining the root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\Core\Kernel;

// running the app'
(new Kernel())->run($rootDir);
