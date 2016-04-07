<?php
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Zend\Expressive\Container\ApplicationFactory;

return [

    'middleware_pipeline' => [
        ['middleware' => CacheMiddleware::class],
        ['middleware' => LanguageMiddleware::class],
        ApplicationFactory::ROUTING_MIDDLEWARE,
        ApplicationFactory::DISPATCH_MIDDLEWARE,
    ]

];
