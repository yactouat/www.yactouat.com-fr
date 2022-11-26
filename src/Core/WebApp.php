<?php

declare(strict_types=1);

namespace App\Core;

use App\Conf;
use App\Constants;
use App\Exceptions\Error\ConfKOException;

/**
 * this class is the entry point of our application
 */
final class WebApp
{
    /** @var Conf the configuration provided to the entry point of the app' */
    private Conf $_conf;

    /** @var int the HTTP status code that will be send in the response */
    private int $_statusCode;

    /**
     * checks app conf, throws if KO
     *
     * @throws ConfKOException
     *
     * @return void
     */
    public function checkConf(): void
    {
        if (!Conf::checkDevConf() || !Conf::checkSharedConf()) {
            throw new ConfKOException();
        }
    }

    /**
     * gets the HTTP response body to be returned to the client
     *
     * TODO test
     *
     * @return string
     */
    public function getResponseBody(): string
    {
        return $this->_conf->twig->render("index.html.twig");
    }

    /**
     * gets the HTTP status code that will be send in the response
     *
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->_statusCode;
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

    public function setStatusCode(): self
    {
        try {
            $this->checkConf();
            $this->_statusCode = Constants::HTTP_OK_CODE;
        } catch (ConfKOException $cke) {
            $this->_statusCode = Constants::HTTP_SERVERERR_CODE;
        }
        return $this;
    }
}
