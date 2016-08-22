<?php
namespace Acelaya\Website\Console\Command;

use Acelaya\Website\Console\Task\LongTaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LongTasksCommand extends Command
{
    /**
     * @var LongTaskInterface[]
     */
    protected $tasks;

    /**
     * LongTasksCommand constructor.
     * @param LongTaskInterface[] $tasks
     */
    public function __construct(array $tasks)
    {
        parent::__construct(null);
        $this->tasks = $tasks;
    }

    public function configure()
    {
        $this->setName('website:long-tasks')
             ->setDescription('Runs all the preconfigured long tasks');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->tasks as $task) {
            $task->run($input, $output);
        }
    }
}
