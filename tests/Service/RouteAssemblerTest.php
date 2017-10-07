<?php
declare(strict_types=1);
namespace AcelayaTest\Website\Service;

use Acelaya\Website\Service\RouteAssembler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;

class RouteAssemblerTest extends TestCase
{
    /**
     * @var RouteAssembler
     */
    protected $assembler;
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var array
     */
    protected $params = ['foo' => 'bar', 'baz' => 'foo'];
    /**
     * @var ObjectProphecy
     */
    protected $prophecyRouter;

    public function setUp()
    {
        $this->request = new ServerRequest();
        $this->prophecyRouter = $this->prophesize(RouterInterface::class);
        $this->prophecyRouter->match($this->request)->willReturn(
            RouteResult::fromRoute(new Route('home', function ($req, $resp) {
                return $resp;
            }), $this->params)
        );

        $this->assembler = new RouteAssembler($this->prophecyRouter->reveal(), $this->request);
    }

    public function testWithoutAnyParam()
    {
        $this->prophecyRouter->generateUri('home', [])->willReturn('/');
        $this->assertEquals('/', $this->assembler->assembleUrl());
    }

    public function testProvidingParams()
    {
        $this->prophecyRouter->generateUri('foo', ['foo' => 'bar'])->willReturn('/foo/bar');
        $this->assertEquals('/foo/bar', $this->assembler->assembleUrl('foo', ['foo' => 'bar']));
    }

    public function testUsingCurrentRouteParams()
    {
        $this->prophecyRouter->generateUri('foo', $this->params)->willReturn('/foo/bar');
        $this->assertEquals('/foo/bar', $this->assembler->assembleUrl('foo', true));
    }

    public function testOverridingCurrentRouteParams()
    {
        $newParams = ['foo' => 'something'];
        $params = array_merge($this->params, $newParams);
        $this->prophecyRouter->generateUri('foo', $params)->willReturn('/foo/bar');
        $this->assertEquals('/foo/bar', $this->assembler->assembleUrl('foo', $newParams, true));
    }

    public function testProvidingQueryParams()
    {
        $this->prophecyRouter->generateUri('foo', [])->willReturn('/foo/bar');
        $this->assertEquals('/foo/bar?foo=bar', $this->assembler->assembleUrl('foo', [], ['foo' => 'bar']));
    }
}
