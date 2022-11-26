<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\HardcodedPersonalIntroService;

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
        $personalIntroService = new HardcodedPersonalIntroService();
        $webApp = (new WebApp())->init($rootDir, $personalIntroService);
        http_response_code($webApp->getStatusCode());
        echo $webApp->getResponseBody();
    }
}
