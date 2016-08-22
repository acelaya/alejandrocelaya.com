<?php
namespace Acelaya\Website\Feed\Service;

use Acelaya\Website\Feed\GuzzleClient;
use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Doctrine\Common\Cache;
use Zend\Feed\Reader\Http\ClientInterface;
use Zend\Feed\Reader\Reader;

class BlogFeedConsumer implements BlogFeedConsumerInterface
{
    /**
     * @var Cache\CacheProvider
     */
    private $cache;

    /**
     * BlogFeedConsumer constructor.
     * @param ClientInterface $httpClient
     * @param Cache\CacheProvider $cache
     *
     * @Inject({GuzzleClient::class, Cache\Cache::class})
     */
    public function __construct(ClientInterface $httpClient, Cache\CacheProvider $cache)
    {
        Reader::setHttpClient($httpClient);
        $this->cache = $cache;
    }
}
