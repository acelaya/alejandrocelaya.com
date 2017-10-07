<?php
declare(strict_types=1);

use Acelaya\Website\Factory\CacheFactory;
use Doctrine\Common\Cache\Cache;

return [

    'cache' => [
        'namespaces' => [
            Cache::class => 'www.alejandrocelaya.com',
            CacheFactory::FEED_CACHE => 'www.alejandrocelaya.com_rss',
            CacheFactory::VIEWS_CACHE => 'www.alejandrocelaya.com_views',
        ],
        'redis' => [
            'scheme' => 'tcp',
            'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
            'port' => getenv('REDIS_PORT') ?: 6379,
        ],
    ],

];
