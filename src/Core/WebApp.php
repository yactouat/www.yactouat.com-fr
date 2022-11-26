<?php

declare(strict_types=1);

namespace App\Core;

use App\Conf;
use App\Constants;
use App\Exceptions\Error\ConfKOException;

/**
 * this class is responsible for building the response that will be sent to the client
 */
final class WebApp
{
    /** @var Conf the configuration provided to the entry point of the app' */
    private Conf $_conf;

    /** @var string the HTTP response body that will be sent */
    private string $_responseBody;

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
     * @return string
     */
    public function getResponseBody(): string
    {
        return $this->_responseBody;
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
     * sets the conf, the HTTP status code, and the response body
     *
     * @return self
     */
    public function init(string $rootDir): self
    {
        return $this->setConf($rootDir)
            ->setStatusCode()
            ->setResponseBody();
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

    /**
     * dynamically sets the response body to send to the client
     *
     * @return self
     */
    public function setResponseBody(): self
    {
        try {
            $this->checkConf();
            $this->_responseBody = $this->_conf->twig->render("index.html.twig");
        } catch (ConfKOException $cke) {
            $this->_responseBody = $this->_conf->twig->render("500_error.html.twig");
        }
        return $this;
    }

    /**
     * dynamically sets the HTTP status code to send to the client
     *
     * @return self
     */
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
