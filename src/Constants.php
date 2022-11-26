<?php

declare(strict_types=1);

namespace App;

/**
 * this class contains the list of all the constants used throughout the app'
 *
 * code is grouped into sections for readability:
 * - env related constants
 * - errors and exceptions related constants
 */
final class Constants
{
    /** @var string key to reference on which env the app' runs */
    public const APP_ENV = 'APP_ENV';
    /** @var string value to reference the dev environment */
    public const DEV_ENV = 'DEV';
    /** @var string root dir if you run the app' in Docker (which is strongly recommended) */
    public const DOCKER_ROOT_DIR = '/var/www/html';
    /** @var string value to reference the prod environment */
    public const PROD_ENV = 'PROD';

    /** @var string conf KO error message */
    public const ERR_EXP_CONFKO = 'conf KO';
}
