<?php
namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\RouterFactory;
use Acelaya\Website\Router\Slim;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class RouterFactoryTest extends TestCase
{
    /**
     * @var RouterFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new RouterFactory();
    }

    public function testInvoke()
    {
        $instance = $this->factory->__invoke(new ServiceManager());
        $this->assertInstanceOf(Slim::class, $instance);
    }
}
