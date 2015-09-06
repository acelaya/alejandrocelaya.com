<?php
namespace AcelayaTest\Website\Service\Factory;

use Acelaya\Website\Service\Factory\RouteAssemblerFactory;
use Acelaya\Website\Service\RouteAssembler;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\ServiceManager\ServiceManager;

class RouteAssemblerFactoryTest extends TestCase
{
    /**
     * @var RouteAssemblerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new RouteAssemblerFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager();
        $sm->setService(RouterInterface::class, $this->prophesize(RouterInterface::class)->reveal());
        $sm->setService(ServerRequestInterface::class, $this->prophesize(ServerRequestInterface::class)->reveal());

        $instance = $this->factory->__invoke($sm);
        $this->assertInstanceOf(RouteAssembler::class, $instance);
    }
}
