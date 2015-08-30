<?php
namespace AcelayaTest\Website\Middleware\Factory;

use Acelaya\Website\Middleware\Factory\LanguageMiddlewareFactory;
use Acelaya\Website\Middleware\LanguageMiddleware;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\ServiceManager;

class LanguageMiddlewareFactoryTest extends TestCase
{
    /**
     * @var LanguageMiddlewareFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new LanguageMiddlewareFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager();
        $sm->setService(Translator::class, Translator::factory([]));
        $sm->setService(RouterInterface::class, $this->prophesize(RouterInterface::class)->reveal());

        $instance = $this->factory->__invoke($sm);
        $this->assertInstanceOf(LanguageMiddleware::class, $instance);
    }
}
