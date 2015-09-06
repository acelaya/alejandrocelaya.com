<?php
namespace AcelayaTest\Website\Action\Factory;

use Acelaya\Website\Action\Contact;
use Acelaya\Website\Action\Factory\ContactFactory;
use Acelaya\Website\Service\ContactService;
use PHPUnit_Framework_TestCase as TestCase;
use ReCaptcha\ReCaptcha;
use Zend\Expressive\Template\TemplateInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class ContactFactoryTest extends TestCase
{
    /**
     * @var ContactFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ContactFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager(new Config([
            'services' => [
                TemplateInterface::class => $this->prophesize(TemplateInterface::class)->reveal(),
                ContactService::class => $this->prophesize(ContactService::class)->reveal(),
                ReCaptcha::class => $this->prophesize(ReCaptcha::class)->reveal()
            ]
        ]));
        $instance = $this->factory->__invoke($sm);
        $this->assertInstanceOf(Contact::class, $instance);
    }
}
