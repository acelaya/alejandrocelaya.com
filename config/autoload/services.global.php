<?php
use Acelaya\Expressive\Factory\SlimRouterFactory;
use Acelaya\Website\Action\Contact;
use Acelaya\Website\Action\Factory\ActionAbstractFactory;
use Acelaya\Website\Factory\CacheFactory;
use Acelaya\Website\Factory\RecaptchaFactory;
use Acelaya\Website\Factory\RendererFactory;
use Acelaya\Website\Factory\RequestFactory;
use Acelaya\Website\Factory\SwiftMailerFactory;
use Acelaya\Website\Factory\TranslatorFactory;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Acelaya\Website\Options\Factory\MailOptionsFactory;
use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service\ContactService;
use Acelaya\Website\Service\ContactServiceInterface;
use Acelaya\Website\Service\RouteAssembler;
use Acelaya\ZsmAnnotatedServices\Factory\V3\AnnotatedFactory;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ServerRequestInterface;
use ReCaptcha\ReCaptcha;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Container\TemplatedErrorHandlerFactory;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\I18n\Translator\Translator;

return [

    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class,

            // Actions
            Contact::class => AnnotatedFactory::class,

            // Services
            TemplateRendererInterface::class => RendererFactory::class,
            ServerRequestInterface::class => RequestFactory::class,
            \Swift_Mailer::class => SwiftMailerFactory::class,
            Translator::class => TranslatorFactory::class,
            RouterInterface::class => SlimRouterFactory::class,
            Cache::class => CacheFactory::class,
            ReCaptcha::class => RecaptchaFactory::class,
            RouteAssembler::class => AnnotatedFactory::class,
            ContactService::class => AnnotatedFactory::class,
            'Zend\Expressive\FinalHandler' => TemplatedErrorHandlerFactory::class,
            ContactFilter::class => AnnotatedFactory::class,

            // Options
            MailOptions::class => MailOptionsFactory::class,

            // Middleware
            CacheMiddleware::class => AnnotatedFactory::class,
            LanguageMiddleware::class => AnnotatedFactory::class,
        ],
        'abstract_factories' => [
            ActionAbstractFactory::class
        ],
        'aliases' => [
            'translator' => Translator::class,
            'request' => ServerRequestInterface::class,
            'renderer' => TemplateRendererInterface::class,
            ContactServiceInterface::class => ContactService::class,
            AnnotatedFactory::CACHE_SERVICE => Cache::class,
        ]
    ]

];
