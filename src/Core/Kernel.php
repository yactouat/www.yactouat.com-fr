<?php

declare(strict_types=1);

namespace App\Core;

/**
 * this responsible for being the entry point to our application
 */
final class Kernel
{
    /**
     * executes a round of HTTP request/response cycle
     *
     * @param string $rootDir of the application
     * @return void
     */
    public function runReqResRound(string $rootDir): void
    {
        $webApp = (new WebApp())->init($rootDir);
        http_response_code($webApp->getStatusCode());
        echo $webApp->getResponseBody();
    }
}
