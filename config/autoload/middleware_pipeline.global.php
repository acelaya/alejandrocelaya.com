<?php
declare(strict_types=1);

use Acelaya\Website\Middleware;
use Acelaya\Website\Service\RouteAssembler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Router;
use Zend\Expressive\Swoole\Log\StdoutLogger;
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
                'request_aware_middleware',
                Middleware\CacheMiddleware::class,
                Middleware\LanguageMiddleware::class,
                Router\Middleware\DispatchMiddleware::class,
                NotFoundHandler::class,
            ],
            'priority' => 1,
        ],
    ],

    'dependencies' => [
        'factories' => [
            'request_aware_middleware' => function (ContainerInterface $container) {
                return \Zend\Stratigility\middleware(function (ServerRequestInterface $req, $handler) use ($container) {
                    $container->get(RouteAssembler::class)->setRequest($req);
                    return $handler->handle($req);
                });
            },
        ],
    ],

];
