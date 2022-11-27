<?php

declare(strict_types=1);

namespace Tests\Integration\Core;

use App\Constants;
use App\Core\Http\Request;
use App\Core\WebApp;
use App\Exceptions\Error\ConfKOException;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Traits\TestConfTrait;
use Yactouat\Dev\StringsComparatorTrait;

final class WebAppTest extends TestCase
{
    use StringsComparatorTrait;
    use TestConfTrait;

    protected function setUp(): void
    {
        $this->setTestConf();
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
        (new WebApp())->checkConf();
    }

    public function testGetResponseBodyGetsIndexPage()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $webApp = (new WebApp())->setConf(Constants::DOCKER_ROOTDIR);
        $webApp->routeTo(new Request());
        $actual = $webApp->getResponseBody();
        $this->assertTrue($this->stringIsContainedInAnother('<title>yactouat.fr | accueil</title>', $actual));
        $this->assertTrue($this->stringIsContainedInAnother('<p class="header_about-text">', $actual));
        $this->assertTrue($this->stringIsContainedInAnother('<h2 class="main_heading">Qui je suis</h2>', $actual));
    }

    public function testGetResponseBodyWithBadConfGets500ErrorPage()
    {
        $expected = \file_get_contents(Constants::DOCKER_FIXTURESDIR . '500_error.html');
        ini_set("display_errors", 0);
        $_SERVER['REQUEST_URI'] = '/';
        $webApp = (new WebApp())->setConf(Constants::DOCKER_ROOTDIR);
        $webApp->routeTo(new Request());
        $actual = $webApp->getResponseBody();
        // $this->assertTrue($this->stringsHaveSameContent($expected, $actual));
        $this->assertSame($this->removeSpacesFromString($expected), $this->removeSpacesFromString($actual));
    }

    public function testGetStatusCodeCodeSets200StatusCode()
    {
        $expected = Constants::HTTP_OK_CODE;
        $_SERVER['REQUEST_URI'] = '/';
        $webApp = (new WebApp())->setConf(Constants::DOCKER_ROOTDIR);
        $webApp->routeTo(new Request());
        $actual = $webApp->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetStatusCodeCodeWithBadConfSets500ErrorCode()
    {
        $expected = Constants::HTTP_SERVERERR_CODE;
        ini_set("display_errors", 0);
        $_SERVER['REQUEST_URI'] = '/';
        $webApp = (new WebApp())->setConf(Constants::DOCKER_ROOTDIR);
        $webApp->routeTo(new Request());
        $actual = $webApp->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseBodyForIndexRouteWithDynamicContentDisplaysDynamicContent()
    {
        $expected = \file_get_contents(Constants::DOCKER_FIXTURESDIR . 'index.html');
        $_SERVER['REQUEST_URI'] = '/';
        $webApp = (new WebApp())->setConf(Constants::DOCKER_ROOTDIR);
        $webApp->routeTo(new Request());
        $webApp->getController()->setResponseData([
            'mainHeading' => 'Qui je suis',
            'sections' => [
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
            ],
            'title' => 'accueil',
            'withShortIntro' => true
        ]);
        $actual = $webApp->getResponseBody();
        $this->assertEquals($this->removeSpacesFromString($expected), $this->removeSpacesFromString($actual));
    }

    public function testGetResponseBodyForLegalNoticeRouteDisplaysLegalNoticePage()
    {
        $expected = \file_get_contents(Constants::DOCKER_FIXTURESDIR . 'legal-notice.html');
        $_SERVER['REQUEST_URI'] = '/mentions-legales';
        $webApp = (new WebApp())->setConf(Constants::DOCKER_ROOTDIR);
        $webApp->routeTo(new Request());
        $actual = $webApp->getResponseBody();
        $this->assertEquals($this->removeSpacesFromString($expected), $this->removeSpacesFromString($actual));
    }
}
