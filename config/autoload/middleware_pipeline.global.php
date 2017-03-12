<?php
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Zend\Expressive\Application;
use Zend\Stratigility\Middleware\ErrorHandler;

return [

    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                LanguageMiddleware::class,
                ErrorHandler::class,
                CacheMiddleware::class,
            ],
            'priority' => 10000,
        ],

        'routing' => [
            'middleware' => [
                Application::ROUTING_MIDDLEWARE,
                Application::DISPATCH_MIDDLEWARE,
            ],
            'priority' => 1,
        ],
    ]

];
