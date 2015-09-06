<?php
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;

return [

    'middleware_pipeline' => [
        'pre_routing' => [
            [
                'middleware' => CacheMiddleware::class
            ],
            [
                'middleware' => LanguageMiddleware::class
            ]
        ],
        'post_routing' => [

        ]
    ]

];
