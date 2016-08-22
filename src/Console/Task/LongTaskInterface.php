<?php
namespace Acelaya\Website\Console\Task;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface LongTaskInterface
{
    public function run(InputInterface $input, OutputInterface $output);
}
