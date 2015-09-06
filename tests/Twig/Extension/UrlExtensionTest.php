<?php
namespace AcelayaTest\Website\Twig\Extension;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Twig\Extension\UrlExtension;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Prophecy\ObjectProphecy;
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
        $this->assertCount(1, $funcs);
        $this->assertEquals('assemble_url', $funcs[0]->getName());
    }

    public function testAssembleUrl()
    {
        $this->assertEquals('/foo/bar', $this->extension->assembleUrl('home'));
    }

    public function testGetCurrentRouteResult()
    {
        $this->routeAssemblerProphezy->getCurrentRouteResult()->willReturn(RouteResult::fromRouteMatch(
            'home',
            'HelloWorld',
            []
        ));
        $result = $this->extension->getCurrentRouteResult();
        $this->assertInstanceOf(RouteResult::class, $result);
    }
}
