<?php
namespace AcelayaTest\Website\Service\Factory;

use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service\ContactService;
use Acelaya\Website\Service\Factory\ContactServiceFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Expressive\Template\TemplateInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class ContactServiceFactoryTest extends TestCase
{
    /**
     * @var ContactServiceFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ContactServiceFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager(new Config([
            'services' => [
                \Swift_Mailer::class => $this->prophesize(\Swift_Mailer::class)->reveal(),
                TemplateInterface::class => $this->prophesize(TemplateInterface::class)->reveal(),
                MailOptions::class => new MailOptions(),
            ]
        ]));
        $instance = $this->factory->__invoke($sm);
        $this->assertInstanceOf(ContactService::class, $instance);
    }
}
