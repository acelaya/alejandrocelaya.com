<?php
declare(strict_types=1);

namespace Acelaya\Website\Feed\Service;

use Acelaya\Website\Feed\BlogOptions;
use Doctrine\Common\Cache;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\Http\ClientInterface;
use Zend\Feed\Reader\Reader;

class BlogFeedConsumer implements BlogFeedConsumerInterface
{
    /** @var BlogOptions */
    private $blogOptions;
    /** @var Cache\Cache */
    private $feedCache;
    /** @var Cache\ClearableCache */
    private $viewsCache;

    public function __construct(
        ClientInterface $httpClient,
        Cache\Cache $feedCache,
        Cache\ClearableCache $viewsCache,
        BlogOptions $blogOptions
    ) {
        Reader::setHttpClient($httpClient);
        $this->blogOptions = $blogOptions;
        $this->feedCache = $feedCache;
        $this->viewsCache = $viewsCache;
    }

    public function refreshFeed(): array
    {
        $cacheId = $this->blogOptions->getCacheKey();
        $feed = Reader::import($this->blogOptions->getFeed());
        $feed = $this->processFeed($feed);

        // If no feed has been cached yet, cache current one and return
        if (! $this->feedCache->contains($cacheId)) {
            $this->viewsCache->deleteAll();
            $this->feedCache->save($cacheId, $feed);
            return $feed;
        }

        // Check if the last feed has changed, otherwise, return
        $cachedFeed = $this->feedCache->fetch($cacheId);
        if ($cachedFeed[0]['link'] === $feed[0]['link']) {
            return $feed;
        }

        // If the feed has changed, clear all cached elements so that views are refreshed, and cache feed too
        $this->viewsCache->deleteAll();
        $this->feedCache->save($cacheId, $feed);
        return $feed;
    }

    /**
     * @param FeedInterface $feed
     * @return array
     */
    protected function processFeed(FeedInterface $feed): array
    {
        $data = [];
        $count = 0;

        /** @var FeedInterface $entry */
        foreach ($feed as $entry) {
            if ($count >= $this->blogOptions->getElementsToDisplay()) {
                break;
            }

            $data[] = [
                'title' => $entry->getTitle(),
                'link' => $entry->getLink(),
            ];
            $count += 1;
        }

        return $data;
    }
}
