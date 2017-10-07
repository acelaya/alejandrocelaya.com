<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Console\Command;

use Acelaya\Website\Console\Command\LongTasksCommand;
use Acelaya\Website\Console\Task\LongTaskInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LongTasksCommandTest extends TestCase
{
    protected $command;
    /**
     * @var ObjectProphecy
     */
    protected $input;
    /**
     * @var ObjectProphecy
     */
    protected $output;

    public function setUp()
    {
        $this->input = $this->prophesize(InputInterface::class)->reveal();
        $this->output = $this->prophesize(OutputInterface::class)->reveal();
    }

    /**
     * @test
     */
    public function allTasksAreRun()
    {
        $task1 = $this->prophesize(LongTaskInterface::class);
        $task1->run($this->input, $this->output)->shouldBeCalledTimes(1);
        $task2 = $this->prophesize(LongTaskInterface::class);
        $task2->run($this->input, $this->output)->shouldBeCalledTimes(1);
        $task3 = $this->prophesize(LongTaskInterface::class);
        $task3->run($this->input, $this->output)->shouldBeCalledTimes(1);

        $this->command = new LongTasksCommand([$task1->reveal(), $task2->reveal(), $task3->reveal()]);
        $this->command->execute($this->input, $this->output);
    }
}
