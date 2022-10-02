<?php

declare(strict_types=1);

namespace App;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader as TwigLoader;

/**
 * this class is responsible for managing configuration for all and each envs
 */
final class Conf
{
    /** @var Environment the Twig instance that will be used in the app' */
    public Environment $twig;

    /** @var string the root directory of the app' */
    private string $_rootDir;

    /**
     * initialization logic of the configuration
     *
     * loads:
     * - twig, a `views` directory must exist at the selected root dir
     *
     * @param string $rootDir the root directory of the project
     *
     * @return void
     */
    public function __construct(string $rootDir)
    {
        $this->_rootDir = $rootDir;
        $this->_initTwig();
    }

    /**
     * initialization logic of Twig
     *
     * adds debug extension if dev to dump values
     *
     * @return void
     */
    private function _initTwig(): void
    {
        $loader = new TwigLoader($this->_rootDir . '/views');
        $this->twig = new Environment($loader, [
            'debug' => self::isDevEnv()
        ]);
        if (self::isDevEnv()) {
            $this->twig->addExtension(new DebugExtension());
        }
    }

    /**
     * checks if dev PHP conf. corresponds to what's expected
     *
     * - `display_errors` is supposed to be on
     * - `display_startup_errors` is supposed to be on
     *
     * @return bool when app' env is set to prod, returns true because there is nothing to check
     */
    public static function checkDevConf(): bool
    {
        if (self::isProdEnv()) {
            return true;
        }
        return ini_get("display_errors") == 1 && ini_get("display_startup_errors") == 1;
    }

    /**
     * checks if shared PHP conf. corresponds to what's expected
     *
     * - `error_reporting` is supposed to be `E_ALL`
     * - `log_errors` is supposed to be on
     *
     * @return bool whether the conf is valid
     */
    public static function checkSharedConf(): bool
    {
        return ini_get("error_reporting") == E_ALL && ini_get("log_errors") == 1;
    }

    /**
     * checks if app' runs in dev environment
     *
     * @return bool
     */
    public static function isDevEnv(): bool
    {
        return getenv(Constants::APP_ENV, true) === Constants::DEV_ENV;
    }

    /**
     * checks if app' runs in prod environment
     *
     * @return bool
     */
    public static function isProdEnv(): bool
    {
        return getenv(Constants::APP_ENV, true) === Constants::PROD_ENV;
    }

    /**
     * gets the root directory of the application
     *
     * this is used to locate various folders:
     * - vendor
     * - views
     *
     * @return string
     */
    public function getRootDir(): string
    {
        return $this->_rootDir;
    }
}
