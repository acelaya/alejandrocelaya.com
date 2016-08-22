<?php
namespace Acelaya\Website\Feed\Service;

interface BlogFeedConsumerInterface
{
    /**
     * Refreshes the feed and returns saved elements
     *
     * @return array
     */
    public function refreshFeed(): array;
}
