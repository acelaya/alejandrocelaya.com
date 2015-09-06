<?php
namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\RecaptchaFactory;
use PHPUnit_Framework_TestCase as TestCase;
use ReCaptcha\ReCaptcha;
use Zend\ServiceManager\ServiceManager;

class RecaptchaFactoryTest extends TestCase
{
    /**
     * @var RecaptchaFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new RecaptchaFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager();
        $sm->setService('config', [
            'recaptcha' => [
                'private_key' => 'foo'
            ]
        ]);
        $instance = $this->factory->__invoke($sm);
        $this->assertInstanceOf(Recaptcha::class, $instance);
    }
}
