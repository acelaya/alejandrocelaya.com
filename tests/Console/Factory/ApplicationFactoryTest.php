<?php
declare(strict_types=1);
namespace AcelayaTest\Website\Console\Factory;

use Acelaya\Website\Console\Factory\ApplicationFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class ApplicationFactoryTest extends TestCase
{
    /**
     * @var ApplicationFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ApplicationFactory();
    }

    /**
     * @test
     */
    public function onlyServiceCommandsAreInjected()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'cli' => [
                'commands' => [
                    'foo',
                    'bar',
                    'baz',
                ],
            ],
        ]);
        $container->has('foo')->willReturn(true);
        $container->has('bar')->willReturn(false);
        $container->has('baz')->willReturn(true);
        $container->get('foo')->willReturn($this->prophesize(Command::class)->reveal())
                              ->shouldBeCalledTimes(1);
        $container->get('baz')->willReturn($this->prophesize(Command::class)->reveal())
                              ->shouldBeCalledTimes(1);

        $instance = $this->factory->__invoke($container->reveal(), '');
        $this->assertInstanceOf(Application::class, $instance);
    }
}
