<?php
namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\SwiftMailerFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class SwiftMailerFactoryTest extends TestCase
{
    /**
     * @var SwiftMailerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new SwiftMailerFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager();
        $sm->setService('config', [
            'mail' => [
                'smtp' => [
                    'server' => 'foo.com',
                    'port' => 25,
                    'ssl' => 'ssl',
                    'username' => 'john@foo.com',
                    'password' => '12345'
                ]
            ]
        ]);

        /** @var \Swift_Mailer $mailer */
        $mailer = $this->factory->__invoke($sm, '');
        $this->assertInstanceOf(\Swift_Mailer::class, $mailer);
        $this->assertInstanceOf(\Swift_SmtpTransport::class, $mailer->getTransport());
    }
}
