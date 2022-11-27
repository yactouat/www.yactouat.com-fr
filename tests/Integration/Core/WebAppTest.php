<?php

declare(strict_types=1);

namespace Tests\Integration\Core;

use App\Constants;
use App\Core\WebApp;
use App\Exceptions\Error\ConfKOException;
use App\Services\PersonalIntroServiceInterface;
use PHPUnit\Framework\TestCase;
use Tests\Integration\TestConfTrait;
use Yactouat\Dev\StringsComparatorTrait;

final class WebAppTest extends TestCase
{
    use StringsComparatorTrait;
    use TestConfTrait;

    protected PersonalIntroServiceInterface $personalIntroService;

    protected function setUp(): void
    {
        $this->setTestConf();
        $this->personalIntroService = new class () implements PersonalIntroServiceInterface {
            public function __construct(private array $_sections = [])
            {
                $this->_sections = [];
            }

            public function getSections(): array
            {
                return $this->_sections;
            }

            public function setSections(array $sections): void
            {
                $this->_sections = $sections;
            }
        };
    }

    protected function tearDown(): void
    {
        $this->setTestConf();
    }

    public function testCheckConfWithBadConfThrowsConfKoException()
    {
        ini_set("display_errors", 0);
        $this->expectException(ConfKOException::class);
        $this->expectExceptionMessage(Constants::ERR_EXP_CONFKO);
        (new WebApp())->init(Constants::DOCKER_ROOTDIR, $this->personalIntroService)->checkConf();
    }

    public function testGetResponseBodyGetsIndexPage()
    {
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR, $this->personalIntroService)
            ->getResponseBody();
        $this->assertTrue($this->stringIsContainedInAnother('<title>yactouat.fr | accueil</title>', $actual));
        $this->assertTrue($this->stringIsContainedInAnother('<p class="header_about-text">', $actual));
        $this->assertTrue($this->stringIsContainedInAnother('<h2 class="main_container_heading">Qui je suis</h2>', $actual));
    }

    public function testGetResponseBodyWithBadConfGets500ErrorPage()
    {
        $expected = \file_get_contents(Constants::DOCKER_FIXTURESDIR . '500_error.html');
        ini_set("display_errors", 0);
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR, $this->personalIntroService)
            ->getResponseBody();
        $this->assertTrue($this->stringsHaveSameContent($expected, $actual));
    }

    public function testGetStatusCodeCodeSets200StatusCode()
    {
        $expected = Constants::HTTP_OK_CODE;
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR, $this->personalIntroService)
            ->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetStatusCodeCodeWithBadConfSets500ErrorCode()
    {
        $expected = Constants::HTTP_SERVERERR_CODE;
        ini_set("display_errors", 0);
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR, $this->personalIntroService)
            ->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseBodyWithDynamicContentDisplaysDynamicContent()
    {
        $expected = \file_get_contents(Constants::DOCKER_FIXTURESDIR . 'index.html');
        $this->personalIntroService->setSections([
            [
                'heading' => 'Test pres section 1 h3',
                'paragraphs' => [
                    'Test pres section 1 p 1.',
                    'Test pres section 1 p 2.'
                ]
            ],
            [
                'heading' => 'Test pres section 2 h3',
                'paragraphs' => [
                    'Test pres section 2 p 1.',
                    'Test pres section 2 p 2.'
                ]
            ]
        ]);
        $actual = (new WebApp())
            ->init(Constants::DOCKER_ROOTDIR, $this->personalIntroService)
            ->getResponseBody();
        // $this->assertTrue($this->stringsHaveSameContent($expected, $actual));
        $this->assertEquals($this->removeSpacesFromString($expected), $this->removeSpacesFromString($actual));
    }
}
