<?php
namespace AcelayaTest\Website\Options\Factory;

use Acelaya\Website\Options\Factory\MailOptionsFactory;
use Acelaya\Website\Options\MailOptions;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

class MailOptionsFactoryTest extends TestCase
{
    /**
     * @var MailOptionsFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new MailOptionsFactory();
    }

    public function testInvoke()
    {
        $sm = new ServiceManager([
            'services' => [
                'config' => [
                    'mail' => []
                ]
            ]
        ]);
        $instance = $this->factory->__invoke($sm, '');
        $this->assertInstanceOf(MailOptions::class, $instance);
    }
}
