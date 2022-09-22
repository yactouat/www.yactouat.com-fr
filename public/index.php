<?php

// defining root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\EntryPoint;

// instanciating app'
$app = new EntryPoint($rootDir);

// send response to request
$app->respond();
