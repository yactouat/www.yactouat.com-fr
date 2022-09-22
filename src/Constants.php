<?php

declare(strict_types=1);

namespace App;

/**
 * this class contains the list of all the constants used throughout the app'
 */
final class Constants
{
    /** @var string key to reference on which env the app' runs */
    public const APP_ENV = "APP_ENV";

    /** @var string value to reference the dev environment */
    public const DEV_ENV = "DEV";

    /** @var string value to reference the prod environment */
    public const PROD_ENV = "PROD";
}
