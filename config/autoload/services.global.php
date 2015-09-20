<?php
use Acelaya\Expressive\Factory\SlimRouterFactory;
use Acelaya\Website\Action\Contact;
use Acelaya\Website\Action\Factory\ActionAbstractFactory;
use Acelaya\Website\Action\Factory\ContactFactory;
use Acelaya\Website\Factory\CacheFactory;
use Acelaya\Website\Factory\RecaptchaFactory;
use Acelaya\Website\Factory\RendererFactory;
use Acelaya\Website\Factory\RequestFactory;
use Acelaya\Website\Factory\SwiftMailerFactory;
use Acelaya\Website\Factory\TranslatorFactory;
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\Factory\CacheMiddlewareFactory;
use Acelaya\Website\Middleware\Factory\LanguageMiddlewareFactory;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Acelaya\Website\Options\Factory\MailOptionsFactory;
use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service\ContactService;
use Acelaya\Website\Service\Factory\ContactServiceFactory;
use Acelaya\Website\Service\Factory\RouteAssemblerFactory;
use Acelaya\Website\Service\RouteAssembler;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ServerRequestInterface;
use ReCaptcha\ReCaptcha;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Container\TemplatedErrorHandlerFactory;
use Zend\Expressive\Container\WhoopsFactory;
use Zend\Expressive\Container\WhoopsPageHandlerFactory;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateInterface;
use Zend\I18n\Translator\Translator;

return [

    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class,

            // Actions
            Contact::class => ContactFactory::class,

            // Services
            TemplateInterface::class => RendererFactory::class,
            ServerRequestInterface::class => RequestFactory::class,
            \Swift_Mailer::class => SwiftMailerFactory::class,
            Translator::class => TranslatorFactory::class,
            RouterInterface::class => SlimRouterFactory::class,
            Cache::class => CacheFactory::class,
            ReCaptcha::class => RecaptchaFactory::class,
            RouteAssembler::class => RouteAssemblerFactory::class,
            ContactService::class => ContactServiceFactory::class,
            'Zend\Expressive\FinalHandler' => TemplatedErrorHandlerFactory::class,
            'Zend\Expressive\Whoops' => WhoopsFactory::class,
            'Zend\Expressive\WhoopsPageHandler' => WhoopsPageHandlerFactory::class,

            // Options
            MailOptions::class => MailOptionsFactory::class,

            // Middleware
            CacheMiddleware::class => CacheMiddlewareFactory::class,
            LanguageMiddleware::class => LanguageMiddlewareFactory::class,
        ],
        'abstract_factories' => [
            ActionAbstractFactory::class
        ],
        'aliases' => [
            'translator' => Translator::class,
            'request' => ServerRequestInterface::class,
            'renderer' => TemplateInterface::class
        ]
    ]

];
