<?php
namespace AcelayaTest\Website\Twig\Extension;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Template\Extension\UrlExtension;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouteResult;

class UrlExtensionTest extends TestCase
{
    /**
     * @var UrlExtension
     */
    protected $extension;
    /**
     * @var ObjectProphecy
     */
    protected $routeAssemblerProphezy;

    public function setUp()
    {
        $this->routeAssemblerProphezy = $this->prophesize(RouteAssembler::class);
        $this->routeAssemblerProphezy->assembleUrl('home', [], [], false)->willReturn('/foo/bar');
        $this->extension = new UrlExtension($this->routeAssemblerProphezy->reveal());
    }

    public function testGetFunctions()
    {
        /** @var \Twig_SimpleFunction[] $funcs */
        $funcs = $this->extension->getFunctions();
        $this->assertCount(2, $funcs);
        $this->assertEquals('assemble_url', $funcs[0]->getName());
        $this->assertEquals('current_route', $funcs[1]->getName());
    }

    public function testAssembleUrl()
    {
        $this->assertEquals('/foo/bar', $this->extension->assembleUrl('home'));
    }

    public function testGetCurrentRouteResult()
    {
        $this->routeAssemblerProphezy->getCurrentRouteResult()->willReturn(RouteResult::fromRoute(new Route(
            'home',
            'HelloWorld'
        )));
        $result = $this->extension->getCurrentRouteResult();
        $this->assertInstanceOf(RouteResult::class, $result);
    }

    public function testGetCurrentRouteName()
    {
        $this->routeAssemblerProphezy->getCurrentRouteResult()->willReturn(
            RouteResult::fromRoute(new Route('foo', ''))
        );
        $this->assertEquals('foo', $this->extension->getCurrentRouteName());
    }
}
