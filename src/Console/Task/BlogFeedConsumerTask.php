<?php
declare(strict_types=1);

namespace Acelaya\Website\Console\Task;

use Acelaya\Website\Feed\Service\BlogFeedConsumerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function print_r;

class BlogFeedConsumerTask implements LongTaskInterface
{
    /** @var BlogFeedConsumerInterface */
    private $blogFeedConsumer;

    public function __construct(BlogFeedConsumerInterface $blogFeedConsumer)
    {
        $this->blogFeedConsumer = $blogFeedConsumer;
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $feed = $this->blogFeedConsumer->refreshFeed();
        if ($output->isVerbose()) {
            $output->writeln(print_r($feed, true));
        }
    }
}
