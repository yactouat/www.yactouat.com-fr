<?php

declare(strict_types=1);

namespace App\Core\Http;

// TODO full impl of HTTP messages interface https://www.php-fig.org/psr/psr-7/
// use Psr\Http\Message\RequestInterface;
// final class Request implements RequestInterface {
final class Request
{
    private array $_headers = [];

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        $this->_headers[$name] = [$value];
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}
