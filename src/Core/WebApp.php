<?php

declare(strict_types=1);

namespace App\Core;

use App\Conf;
use App\Controllers\IndexController;
use App\Controllers\LegalNoticeController;
use App\Controllers\ServerErrorController;
use App\Core\Http\Request;
use App\Exceptions\Error\ConfKOException;

/**
 * this class is responsible for building the response that will be sent to the client
 */
final class WebApp
{
    /**
     * the web app's configuration (Twig, ini values, etc.)
     *
     * @var Conf
     */
    private Conf $_conf;

    private Controller $_controller;

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
     * gets the controller in charge of building the constituants of the response
     *
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->_controller;
    }

    /**
     * gets the HTTP response body to be returned to the client
     *
     * @return string
     */
    public function getResponseBody(): string
    {
        return $this->_conf->twig->render(
            $this->_controller->getResponseTemplate(),
            $this->_controller->getResponseData()
        );
    }

    /**
     * gets the HTTP status code that will be send in the response
     *
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->_controller->getStatusCode();
    }

    /**
     * sets the HTTP status code and the response body based on the input request
     *
     * @return void
     */
    public function routeTo(Request $request): void
    {
        try {
            $this->checkConf();
            $ctlr = match ($request->getUri()) {
                '/' => IndexController::class,
                '/mentions-legales' => LegalNoticeController::class,
                default => ServerErrorController::class
            };
            $this->_controller = (new $ctlr());
        } catch (ConfKOException $e) {
            $this->_controller = new ServerErrorController();
        }
        $this->_controller->setResponseData();
        $this->_controller->setStatusCode();
        $this->_controller->setResponseTemplate();
    }

    /**
     * sets the conf of the web app'
     *
     * @param string $rootDir
     * @return self
     */
    public function setConf(string $rootDir): static
    {
        $this->_conf = new Conf($rootDir);
        return $this;
    }
}
