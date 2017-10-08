<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Template\Extension;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Template\Extension\UrlExtension;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
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

    public function testRegister()
    {
        $engine = $this->prophesize(Engine::class);

        $engine->registerFunction('assemble_url', Argument::type('callable'))->shouldBeCalledTimes(1);
        $engine->registerFunction('current_route', Argument::type('callable'))->shouldBeCalledTimes(1);

        $this->extension->register($engine->reveal());
    }

    public function testGetCurrentRouteName()
    {
        $this->routeAssemblerProphezy->getCurrentRouteResult()->willReturn(
            RouteResult::fromRoute(new Route('foo', ''))
        );
        $this->assertEquals('foo', $this->extension->getCurrentRouteName());
    }
}
