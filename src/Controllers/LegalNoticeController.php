<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Constants;
use App\Core\Controller;

/**
 * controller responsible for main page output
 */
final class LegalNoticeController extends Controller
{
    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setResponseData(?array $data = null): static
    {
        $this->_responseData = is_null($data) ? [
            'mainHeading' => 'Mentions légales',
            'sections' => [
                [
                    'heading' => 'Directeur des publications:',
                    'paragraphs' => [
                        'Nom: Yacine Touati.',
                        'Adresse: 41 rue Kamm, 67000 Strasbourg, France.'
                    ]
                ],
                [
                    'heading' => 'Informations sur l\'hébergeur:',
                    'paragraphs' => [
                        'Nom: Google Ireland Limited.',
                        'Raison Sociale: Google Cloud Platform.',
                        'Adresse: Gordon House, Barrow Street, Dublin 4, Ireland.',
                        'Téléphone: +35314361000.'
                    ]
                ]
            ],
            'title' => 'mentions légales',
            'withoutLegalNoticeLink' => true
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
