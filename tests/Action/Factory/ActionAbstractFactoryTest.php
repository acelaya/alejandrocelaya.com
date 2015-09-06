<?php
namespace AcelayaTest\Website\Action\Factory;

use Acelaya\Website\Action\Factory\ActionAbstractFactory;
use Acelaya\Website\Action\Template;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Expressive\Template\TemplateInterface;
use Zend\ServiceManager\ServiceManager;

class ActionAbstractFactoryTest extends TestCase
{
    /**
     * @var ActionAbstractFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ActionAbstractFactory();
    }

    public function testCanCreateServiceWithName()
    {
        $sm = new ServiceManager();
        $this->assertFalse($this->factory->canCreateServiceWithName($sm, '', ''));
        $this->assertFalse($this->factory->canCreateServiceWithName($sm, '', 'invalid'));
        $this->assertFalse($this->factory->canCreateServiceWithName($sm, '', \stdClass::class));
        $this->assertTrue($this->factory->canCreateServiceWithName($sm, '', Template::class));
    }

    public function testCreateServiceWithName()
    {
        $sm = new ServiceManager();
        $sm->setService(Cache::class, new ArrayCache());
        $sm->setService('renderer', $this->prophesize(TemplateInterface::class)->reveal());

        $service = $this->factory->createServiceWithName($sm, '', Template::class);
        $this->assertInstanceOf(Template::class, $service);
    }
}
