<?php
namespace AcelayaTest\Website\Middleware\Factory;

use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\Factory\CacheMiddlewareFactory;
use Doctrine\Common\Cache\Cache;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Expressive\Router\RouterInterface;
use Zend\ServiceManager\ServiceManager;

class CacheMiddlewareFactoryTest extends TestCase
{
    /**
     * @var CacheMiddlewareFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new CacheMiddlewareFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager();
        $sm->setService(Cache::class, $this->prophesize(Cache::class)->reveal());
        $sm->setService(RouterInterface::class, $this->prophesize(RouterInterface::class)->reveal());

        $instance = $this->factory->__invoke($sm, '');
        $this->assertInstanceOf(CacheMiddleware::class, $instance);
    }
}
