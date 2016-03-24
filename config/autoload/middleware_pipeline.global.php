<?php
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;

return [

    'middleware_pipeline' => [
        ['middleware' => CacheMiddleware::class],
        ['middleware' => LanguageMiddleware::class],
        Zend\Expressive\Container\ApplicationFactory::ROUTING_MIDDLEWARE,
        Zend\Expressive\Container\ApplicationFactory::DISPATCH_MIDDLEWARE,
    ]

];
