<?php

namespace Tests\Unit\Core\Http;

use App\Core\Http\Request;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    public function testWithHeaderReturnsInstanceOfRequest()
    {
        $expected = Request::class;
        $actual = (new Request())->withHeader('foo', 'bar');
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGetHeadersReturnsEmptyArrayWhenNoHeaderSet()
    {
        $expected = [];
        $actual = (new Request())->getHeaders();
        $this->assertEquals($expected, $actual);
    }

    public function testWithHeaderCreatesCorrectInstanceReqHeader()
    {
        $expected = [
            'foo' => [
                'bar'
            ]
        ];
        $req = new Request();
        $req->withHeader('foo', 'bar');
        $actual = $req->getHeaders();
        $this->assertEquals($expected, $actual);
    }

    public function testWithHeaderModfiesReqHeaderCorrectly()
    {
        $expected = [
            'foo' => [
                'baz'
            ]
        ];
        $req = new Request();
        $req->withHeader('foo', 'bar');
        $req->withHeader('foo', 'baz');
        $actual = $req->getHeaders();
        $this->assertEquals($expected, $actual);
    }

    public function testWithHeaderPreservesCasing()
    {
        $expected = [
            'fOo' => [
                'baR'
            ]
        ];
        $req = new Request();
        $req->withHeader('fOo', 'baR');
        $actual = $req->getHeaders();
        $this->assertEquals($expected, $actual);
    }
}
