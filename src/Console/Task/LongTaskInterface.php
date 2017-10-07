<?php
declare(strict_types=1);

namespace Acelaya\Website\Console\Task;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface LongTaskInterface
{
    public function run(InputInterface $input, OutputInterface $output);
}
