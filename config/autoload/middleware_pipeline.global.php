<?php
use Acelaya\Website\Middleware\CacheMiddleware;

return [

    'middleware_pipeline' => [
        'pre_routing' => [
            [
                'middleware' => CacheMiddleware::class
            ]
        ],
        'post_routing' => [

        ]
    ]

];
