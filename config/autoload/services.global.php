<?php
use Acelaya\Expressive\Factory\SlimRouterFactory;
use Acelaya\Website\Action\Contact;
use Acelaya\Website\Action\Template;
use Acelaya\Website\Console;
use Acelaya\Website\Factory;
use Acelaya\Website\Feed;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Acelaya\Website\Options\Factory\MailOptionsFactory;
use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service;
use Acelaya\ZsmAnnotatedServices\Factory\V3\AnnotatedFactory;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ServerRequestInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Component\Console as Symfony;
use Zend\Expressive;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'service_manager' => [
        'factories' => [
            Expressive\Application::class => Expressive\Container\ApplicationFactory::class,

            // Actions
            Contact::class => AnnotatedFactory::class,
            Template::class => AnnotatedFactory::class,

            // Services
            Expressive\Template\TemplateRendererInterface::class => Factory\RendererFactory::class,
            Expressive\Router\RouterInterface::class => SlimRouterFactory::class,
            'Zend\Expressive\FinalHandler' => Expressive\Container\TemplatedErrorHandlerFactory::class,

            ServerRequestInterface::class => Factory\RequestFactory::class,
            \Swift_Mailer::class => Factory\SwiftMailerFactory::class,
            Translator::class => Factory\TranslatorFactory::class,
            Cache::class => Factory\CacheFactory::class,
            Factory\CacheFactory::FEED_CACHE => Factory\CacheFactory::class,
            ReCaptcha::class => Factory\RecaptchaFactory::class,
            Service\RouteAssembler::class => AnnotatedFactory::class,
            Service\ContactService::class => AnnotatedFactory::class,
            ContactFilter::class => AnnotatedFactory::class,
            Feed\GuzzleClient::class => InvokableFactory::class,
            Feed\Service\BlogFeedConsumer::class => AnnotatedFactory::class,

            // Console
            Symfony\Application::class => Console\Factory\ApplicationFactory::class,
            Console\Command\LongTasksCommand::class => Console\Command\LongTaskCommandFactory::class,
            Console\Task\BlogFeedConsumerTask::class => AnnotatedFactory::class,

            // Options
            MailOptions::class => MailOptionsFactory::class,
            Feed\BlogOptions::class => AnnotatedFactory::class,

            // Middleware
            CacheMiddleware::class => AnnotatedFactory::class,
            LanguageMiddleware::class => AnnotatedFactory::class,
        ],
        'aliases' => [
            'translator' => Translator::class,
            'request' => ServerRequestInterface::class,
            'renderer' => Expressive\Template\TemplateRendererInterface::class,
            Service\ContactServiceInterface::class => Service\ContactService::class,
            AnnotatedFactory::CACHE_SERVICE => Cache::class,
        ]
    ]

];
