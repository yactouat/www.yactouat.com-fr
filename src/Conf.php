<?php

declare(strict_types=1);

namespace App;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader as TwigLoader;
use Twig\TwigFilter;

/**
 * this class is responsible for managing configuration for all and each envs
 */
final class Conf
{
    /** @var Environment the Twig instance that will be used in the app' */
    public Environment $twig;

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
    public function __construct(private string $rootDir)
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
            'debug' => self::isDevEnv(),
            'cache' => false
        ]);
        if (self::isDevEnv()) {
            $this->twig->addExtension(new DebugExtension());
        }
        $sectionsFilter = new TwigFilter('parseSection', function ($val) {
            $section = '<section class="main_container_section">
                <h3 class="main_container_section_heading">'
                    . $val['heading']
                . '</h3>
                <div class="main_container_section_text">';
            $areParagraphs = $val['paragraphs'] ?? false;
            $areListItems= $val['listItems'] ?? false;
            if ($areParagraphs) {
                foreach ($val['paragraphs'] as $p) {
                    $section .= '<p>' . $p . '</p>';
                }
            }
            if ($areListItems) {
                $section .= '<ul>';
                foreach ($val['listItems'] as $li) {
                    $section .= '<li>' . $li . '</li>';
                }
                $section .= '</ul>';
            }
            $section .= '</div></section>';
            return $section;
        });
        $this->twig->addFilter($sectionsFilter);
        $this->twig->addGlobal('copyrightDate', intval(date('Y')) > 2022 ? '2022-' . date('Y') : '2022');
    }

    /**
     * checks if dev PHP conf. corresponds to what's expected
     *
     * - `display_errors` is supposed to be on
     * - `display_startup_errors` is supposed to be on
     * when app' env is set to prod, returns true because there is nothing to check
     *
     * @return bool
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
        $env = $_ENV[Constants::APP_ENV] ?? getenv(Constants::APP_ENV);
        return $env === Constants::DEV_ENV;
    }

    /**
     * checks if app' runs in prod environment
     *
     * @return bool
     */
    public static function isProdEnv(): bool
    {
        $env = $_ENV[Constants::APP_ENV] ?? getenv(Constants::APP_ENV);
        return $env === Constants::PROD_ENV;
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
