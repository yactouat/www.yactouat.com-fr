<?php

declare(strict_types=1);

namespace App;

/**
 * this class contains the list of all the constants used throughout the app'
 *
 * code is grouped into sections for readability:
 * - env related constants
 * - errors and exceptions related constants
 * - HTTP status codes
 */
final class Constants
{
    /** @var string key to reference on which env the app' runs */
    public const APP_ENV = 'APP_ENV';
    /** @var string value to reference the dev environment */
    public const DEV_ENV = 'DEV';
    /** @var string root dir if you run the app' in Docker (which is strongly recommended) */
    public const DOCKER_ROOTDIR = '/var/www/html';
    /** @var string tests fixtures dir if you run the app' in Docker (which is strongly recommended) */
    public const DOCKER_FIXTURESDIR = '/var/www/html/tests/fixtures/';
    /** @var string value to reference the prod environment */
    public const PROD_ENV = 'PROD';

    /** @var string conf KO error message */
    public const ERR_EXP_CONFKO = 'conf KO';
    public const EXP_PEMPTY = 'paragraph cannot be empty';
    public const EXP_SECTEMPTY = 'section cannot be empty';

    /** @var string response OK HTTP status code */
    public const HTTP_OK_CODE = 200;
    /** @var string server error HTTP status code */
    public const HTTP_SERVERERR_CODE = 500;
}
