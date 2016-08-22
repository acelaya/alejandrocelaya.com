<?php
namespace Acelaya\Website\Console\Task;

use Acelaya\Website\Feed\Service\BlogFeedConsumer;
use Acelaya\Website\Feed\Service\BlogFeedConsumerInterface;
use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlogFeedConsumerTask implements LongTaskInterface
{
    /**
     * @var BlogFeedConsumerInterface
     */
    private $blogFeedConsumer;

    /**
     * BlogFeedConsumerTask constructor.
     * @param BlogFeedConsumerInterface $blogFeedConsumer
     *
     * @Inject({BlogFeedConsumer::class})
     */
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
