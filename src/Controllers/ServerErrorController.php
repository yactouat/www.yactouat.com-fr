<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Constants;
use App\Core\Controller;

/**
 * controller responsible for server error output
 */
final class ServerErrorController extends Controller
{
    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setResponseData(?array $data = null): static
    {
        $this->_responseData = [
            'title' => 'erreur serveur'
        ];
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setResponseTemplate(): static
    {
        $this->_responseTemplate = '500_error.html.twig';
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setStatusCode(): static
    {
        $this->_statusCode = Constants::HTTP_SERVERERR_CODE;
        return $this;
    }
}
