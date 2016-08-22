<?php
namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\RendererFactory;
use Acelaya\Website\Feed\BlogOptions;
use Acelaya\Website\Service\RouteAssembler;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\I18n\Translator\Translator;

class RendererFactoryTest extends TestCase
{
    /**
     * @var RendererFactory
     */
    protected $factory;
    /**
     * @var ContainerInterface|ObjectProphecy
     */
    protected $container;

    public function setUp()
    {
        $this->factory = new RendererFactory();
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->container->get('translator')->willReturn(Translator::factory([]));
        $this->container->get('config')->willReturn([
            'navigation' => [],
            'recaptcha' => []
        ]);
        $this->container->get(Cache::class)->willReturn(new ArrayCache());
        $this->container->get(BlogOptions::class)->willReturn(new BlogOptions());
        $this->container->get(RouteAssembler::class)->willReturn($this->prophesize(RouteAssembler::class)->reveal());
    }

    public function testInvoke()
    {
        $instance = $this->factory->__invoke($this->container->reveal(), '');
        $this->assertInstanceOf(TwigRenderer::class, $instance);
    }
}
