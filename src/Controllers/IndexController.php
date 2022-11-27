<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Constants;
use App\Core\Controller;
use App\Services\HardcodedPersonalIntroService;

/**
 * controller responsible for main page output
 */
final class IndexController extends Controller
{
    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setResponseData(?array $data = null): static
    {
        $this->_responseData = is_null($data) ? [
            'mainHeading' => 'Qui je suis',
            'personalIntroSections' => (new HardcodedPersonalIntroService())->getSections(),
            'title' => 'accueil',
            'withShortIntro' => true
        ] : $data;
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setResponseTemplate(): static
    {
        $this->_responseTemplate = 'index.html.twig';
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setStatusCode(): static
    {
        $this->_statusCode = Constants::HTTP_OK_CODE;
        return $this;
    }
}
