<?php

declare(strict_types=1);

namespace App\Core;

final class Kernel
{
    public function run(string $rootDir): void
    {
        // TODO test http code + server error page
        // TODO log exception message
        // TODO test
        $webApp = (new WebApp())
            ->setConf($rootDir)
            ->setStatusCode();
        http_response_code($webApp->getStatusCode());
        echo $webApp->getResponseBody();
    }
}
