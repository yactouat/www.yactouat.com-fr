<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Http\Request;

/**
 * this responsible for being the entry point to our application
 */
final class Kernel
{
    /**
     * holds the request object representing the client's request
     *
     * @var Request
     */
    private Request $_request;

    /**
     * the root dir of the application's code
     *
     * @var string
     */
    private string $_rootDir;

    public function getRequest(): Request
    {
        return $this->_request;
    }

    /**
     * parses the client's HTTP request into a Request object
     *
     * @return static
     */
    public function parseClientRequest(): static
    {
        $this->_request =  new Request();
        return $this;
    }

    /**
     * sends HTTP response to the client
     *
     * @param string $rootDir of the application
     * @return void
     */
    public function sendResponse(): static
    {
        // let the web app' process the request
        $webApp = (new WebApp())->setConf($this->_rootDir);
        $webApp->routeTo($this->_request);
        // send the response back !
        http_response_code($webApp->getStatusCode());
        echo $webApp->getResponseBody();
        // returning the kernel, in case we need to do some post processing for instance
        return $this;
    }

    /**
     * setting the application's code known root directory
     *
     * @param string $rootDir
     * @return static
     */
    public function setRootDir(string $rootDir): static
    {
        $this->_rootDir = $rootDir;
        return $this;
    }
}
