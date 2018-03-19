<?php
declare(strict_types=1);

use Acelaya\Website\Middleware;
use Zend\Expressive\Router;
use Zend\Stratigility\Middleware\ErrorHandler;

return [

    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                ErrorHandler::class,
            ],
            'priority' => 10000,
        ],

        'routing' => [
            'middleware' => [
                Router\Middleware\RouteMiddleware::class,
                Middleware\CacheMiddleware::class,
                Middleware\LanguageMiddleware::class,
                Router\Middleware\DispatchMiddleware::class,
            ],
            'priority' => 1,
        ],
    ],

];
