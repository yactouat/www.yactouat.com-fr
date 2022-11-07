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
     * creates a new instance of the app'
     *
     * @param string $rootDir
     *
     * @return void
     */
    public function __construct(string $rootDir)
    {
        $this->_conf = new Conf($rootDir);
    }

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
        // checking if shared (and dev if relevant) configurations are properly set
        if (!Conf::checkDevConf() || !Conf::checkSharedConf()) {
            http_response_code(500);
            die("conf KO");
        }
        http_response_code(200);
        echo $this->_conf->twig->render("index.html.twig");
    }
}
