<?php

declare(strict_types=1);

namespace App\Core;

/**
 * this class represents what a web controller should do and what properties it should hold
 */
abstract class Controller
{
    /** @var array the response body data that will be eventually sent to the client */
    protected array $_responseData;

    /** @var string the response body template that will make up the response to the client */
    protected string $_responseTemplate;

    /** @var int the HTTP status code that will be send in the response to the client */
    protected int $_statusCode;

    /**
     * gets the response body data to be returned to the client
     *
     * @return array
     */
    public function getResponseData(): array
    {
        return $this->_responseData;
    }

    /**
     * gets the response body data to be returned to the client
     *
     * @return string
     */
    public function getResponseTemplate(): string
    {
        return $this->_responseTemplate;
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
     * dynamically sets the response body data to send to the client
     *
     * @param array|null $data data to the pass to the view
     * @return self
     */
    abstract public function setResponseData(?array $data = null): static;

    /**
     * dynamically sets the response body template that will be used to send the response to the client
     *
     * @return self
     */
    abstract public function setResponseTemplate(): static;

    /**
     * dynamically sets the HTTP status code to send to the client
     *
     * @return self
     */
    abstract public function setStatusCode(): static;
}
