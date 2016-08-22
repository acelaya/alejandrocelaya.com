<?php
namespace AcelayaTest\Website\Console\Task;

use Acelaya\Website\Console\Task\BlogFeedConsumerTask;
use Acelaya\Website\Feed\Service\BlogFeedConsumer;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlogFeedConsumerTaskTest extends TestCase
{
    /**
     * @var BlogFeedConsumerTask
     */
    protected $task;
    /**
     * @var ObjectProphecy
     */
    protected $blogFeedConsumer;

    public function setUp()
    {
        $this->blogFeedConsumer = $this->prophesize(BlogFeedConsumer::class);
        $this->task = new BlogFeedConsumerTask($this->blogFeedConsumer->reveal());
    }

    /**
     * @test
     */
    public function runningTheTaskConsumesTheBlog()
    {
        $this->blogFeedConsumer->refreshFeed()->shouldBeCalledTimes(1);
        $this->task->run(
            $this->prophesize(InputInterface::class)->reveal(),
            $this->prophesize(OutputInterface::class)->reveal()
        );
    }

    /**
     * @test
     */
    public function verboseOutputsResult()
    {
        $output = $this->prophesize(OutputInterface::class);
        $output->isVerbose()->willReturn(true);
        $output->writeln(Argument::cetera())->shouldBeCalledTimes(1);
        $this->blogFeedConsumer->refreshFeed()->shouldBeCalledTimes(1);

        $this->task->run(
            $this->prophesize(InputInterface::class)->reveal(),
            $output->reveal()
        );
    }
}
