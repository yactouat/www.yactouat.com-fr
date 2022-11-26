<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\Error\ConfKOException;

final class Kernel
{
    public function run(string $rootDir): void
    {
        // sending response to client request
        try {
            (new WebApp())->setConf($rootDir)->sendResponse();
        } catch (ConfKOException $cke) {
            // TODO test http code + server error page
            http_response_code(500);
            echo $cke->getMessage();
        }
    }
}
