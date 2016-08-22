<?php
namespace AcelayaTest\Website\Console\Command;

use Acelaya\Website\Console\Command\LongTaskCommandFactory;
use Acelaya\Website\Console\Command\LongTasksCommand;
use Acelaya\Website\Console\Task\LongTaskInterface;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;

class LongTaskCommandFactoryTest extends TestCase
{
    /**
     * @var LongTaskCommandFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new LongTaskCommandFactory();
    }

    /**
     * @test
     */
    public function onlyServiceTasksAreRegistered()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'long_tasks' => [
                'tasks' => [
                    'foo',
                    'bar',
                    'baz',
                ],
            ],
        ]);
        $container->has('foo')->willReturn(true);
        $container->has('bar')->willReturn(false);
        $container->has('baz')->willReturn(true);
        $container->get('foo')->willReturn($this->prophesize(LongTaskInterface::class)->reveal())
                              ->shouldBeCalledTimes(1);
        $container->get('baz')->willReturn($this->prophesize(LongTaskInterface::class)->reveal())
                              ->shouldBeCalledTimes(1);

        $instance = $this->factory->__invoke($container->reveal(), '');
        $this->assertInstanceOf(LongTasksCommand::class, $instance);
    }
}
