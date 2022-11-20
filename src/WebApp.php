<?php

declare(strict_types=1);

namespace App;

/**
 * this class is the entry point of our application
 */
final class WebApp
{
    /** @var Conf the configuration provided to the entry point of the app' */
    private Conf $_conf;

    /**
     * responds to clients requests
     *
     * checks if shared (and dev if relevant) configurations are properly set before actually sending the expected response
     *
     * @param string $rootDir
     *
     * @return void
     */
    public function sendResponse(): void
    {
        // TODO move this logic elsewhere and/or test exception
        // checking if shared (and dev if relevant) configurations are properly set
        if (!Conf::checkDevConf() || !Conf::checkSharedConf()) {
            http_response_code(500);
            die("conf KO");
        }
        // TODO move setting the HTTP response elsewhere and/or test exception
        http_response_code(200);
        echo $this->_conf->twig->render("index.html.twig");
    }

    /**
     * sets the conf of the web app'
     *
     * @param string $rootDir
     * @return self
     */
    public function setConf(string $rootDir): self
    {
        $this->_conf = new Conf($rootDir);
        return $this;
    }
}
