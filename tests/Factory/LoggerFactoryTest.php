<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\LoggerFactory;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use function sys_get_temp_dir;
use function tempnam;

class LoggerFactoryTest extends TestCase
{
    /** @var LoggerFactory */
    private $factory;

    public function setUp()
    {
        $this->factory = new LoggerFactory();
    }

    /**
     * @test
     */
    public function serviceIsProperlyCreated()
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [
                'logger' => ['file' => tempnam(sys_get_temp_dir(), 'acelaya')],
            ],
        ]]), '');

        $this->assertInstanceOf(Logger::class, $instance);
    }
}
