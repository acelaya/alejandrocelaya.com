<?php
declare(strict_types=1);

use Acelaya\Website\Middleware;
use Zend\Expressive\Application;
use Zend\Stratigility\Middleware\ErrorHandler;

return [

    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                ErrorHandler::class,
                Middleware\CacheMiddleware::class,
                Middleware\LanguageMiddleware::class,
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
    ],

];
