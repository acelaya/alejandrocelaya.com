<?php
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Zend\Expressive\Container\ApplicationFactory;

return [

    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                CacheMiddleware::class,
                LanguageMiddleware::class,
            ],
            'priority' => 10000,
        ],

        'routing' => [
            'middleware' => [
                ApplicationFactory::ROUTING_MIDDLEWARE,
                ApplicationFactory::DISPATCH_MIDDLEWARE,
            ],
            'priority' => 1,
        ],
    ]

];
