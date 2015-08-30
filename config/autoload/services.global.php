<?php
use Acelaya\Website\Action\Factory\ActionAbstractFactory;
use Acelaya\Website\Factory\CacheFactory;
use Acelaya\Website\Factory\RendererFactory;
use Acelaya\Website\Factory\RequestFactory;
use Acelaya\Website\Factory\RouterFactory;
use Acelaya\Website\Factory\TranslatorFactory;
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\Factory\CacheMiddlewareFactory;
use Acelaya\Website\Middleware\Factory\LanguageMiddlewareFactory;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Acelaya\Website\Service\Factory\RouteAssemblerFactory;
use Acelaya\Website\Service\RouteAssembler;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\Translator;

return [

    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class,

            // Services
            'renderer' => RendererFactory::class,
            ServerRequestInterface::class => RequestFactory::class,
            Translator::class => TranslatorFactory::class,
            RouterInterface::class => RouterFactory::class,
            Cache::class => CacheFactory::class,
            RouteAssembler::class => RouteAssemblerFactory::class,

            // Middleware
            CacheMiddleware::class => CacheMiddlewareFactory::class,
            LanguageMiddleware::class => LanguageMiddlewareFactory::class,
        ],
        'abstract_factories' => [
            ActionAbstractFactory::class
        ],
        'aliases' => [
            'translator' => Translator::class,
            'request' => ServerRequestInterface::class
        ]
    ]

];
