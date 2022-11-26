<?php

declare(strict_types=1);

namespace App\Core;

use App\Conf;
use App\Exceptions\Error\ConfKOException;

/**
 * this class is the entry point of our application
 */
final class WebApp
{
    /** @var Conf the configuration provided to the entry point of the app' */
    private Conf $_conf;

    /**
     * checks app conf, throws if KO
     *
     * @throws ConfKOException
     *
     * @return void
     */
    private function _checkConf(): void
    {
        if (!Conf::checkDevConf() || !Conf::checkSharedConf()) {
            throw new ConfKOException();
        }
    }

    /**
     * responds to clients requests
     *
     * checks if shared (and dev if relevant) configurations are properly set before actually sending the expected response
     *
     * @param string $rootDir
     *
     * @throws ConfKOException
     *
     * @return void
     */
    public function sendResponse(): void
    {
        $this->_checkConf();
        // TODO move setting the HTTP response code elsewhere
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
