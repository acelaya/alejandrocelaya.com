<?php
declare(strict_types=1);

use Acelaya\Website\Action;
use Acelaya\Website\Console;
use Acelaya\Website\Factory;
use Acelaya\Website\Feed;
use Acelaya\Website\Feed\Template\Extension\BlogExtension;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Middleware;
use Acelaya\Website\Service;
use Acelaya\Website\Template\Extension;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Component\Console as Symfony;
use Zend\Expressive;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Stratigility\Middleware\ErrorHandler;

return [

    'dependencies' => [
        'factories' => [
            // Actions
            Action\Contact::class => ConfigAbstractFactory::class,
            Action\Template::class => ConfigAbstractFactory::class,

            // Services
            LoggerInterface::class => Factory\LoggerFactory::class,
            Translator::class => Factory\TranslatorFactory::class,
            ReCaptcha::class => Factory\RecaptchaFactory::class,
            Service\RouteAssembler::class => ConfigAbstractFactory::class,
            Service\ContactService::class => ConfigAbstractFactory::class,
            ContactFilter::class => ConfigAbstractFactory::class,
            Feed\GuzzleClient::class => InvokableFactory::class,
            Feed\Service\BlogFeedConsumer::class => ConfigAbstractFactory::class,

            // Template extensions
            Extension\TranslatorExtension::class => ConfigAbstractFactory::class,
            Extension\UrlExtension::class => ConfigAbstractFactory::class,
            Extension\NavigationExtension::class => ConfigAbstractFactory::class,
            Extension\RecaptchaExtension::class => ConfigAbstractFactory::class,
            BlogExtension::class => ConfigAbstractFactory::class,

            Cache::class => Factory\CacheFactory::class,
            Factory\CacheFactory::VIEWS_CACHE => Factory\CacheFactory::class,
            Factory\CacheFactory::FEED_CACHE => Factory\CacheFactory::class,

            // Console
            Symfony\Application::class => Console\Factory\ApplicationFactory::class,
            Console\Command\LongTasksCommand::class => Console\Command\LongTaskCommandFactory::class,
            Console\Task\BlogFeedConsumerTask::class => ConfigAbstractFactory::class,

            // Options
            Feed\BlogOptions::class => ConfigAbstractFactory::class,

            // Middleware
            Middleware\CacheMiddleware::class => ConfigAbstractFactory::class,
            Middleware\LanguageMiddleware::class => ConfigAbstractFactory::class,
        ],

        'aliases' => [
            'translator' => Translator::class,
            'request' => ServerRequestInterface::class,
            'renderer' => Expressive\Template\TemplateRendererInterface::class,
            Service\ContactServiceInterface::class => Service\ContactService::class,
        ],

        'abstract_factories' => [
            Factory\DotNotationConfigAbstractFactory::class,
        ],

        'delegators' => [
            ErrorHandler::class => [
                Factory\ErrorHandlerDelegator::class,
            ],
            Expressive\Application::class => [
                Expressive\Container\ApplicationConfigInjectionDelegator::class,
            ],
        ],
    ],

    ConfigAbstractFactory::class => [
        Extension\TranslatorExtension::class => ['translator'],
        Extension\UrlExtension::class => [Service\RouteAssembler::class],
        Extension\NavigationExtension::class => ['translator', Service\RouteAssembler::class, 'config.navigation'],
        Extension\RecaptchaExtension::class => ['config.recaptcha'],
        BlogExtension::class => [Factory\CacheFactory::FEED_CACHE, Feed\BlogOptions::class],
        Action\Contact::class => ['renderer', Service\ContactService::class, ContactFilter::class],
        Action\Template::class => ['renderer'],
        Service\RouteAssembler::class => [Expressive\Router\RouterInterface::class, 'request'],
        Service\ContactService::class => ['acmailer.mailservice.default'],
        ContactFilter::class => [ReCaptcha::class],
        Feed\Service\BlogFeedConsumer::class => [
            Feed\GuzzleClient::class,
            Factory\CacheFactory::FEED_CACHE,
            Factory\CacheFactory::VIEWS_CACHE,
            Feed\BlogOptions::class,
        ],
        Console\Task\BlogFeedConsumerTask::class => [Feed\Service\BlogFeedConsumer::class],
        Feed\BlogOptions::class => ['config.blog'],
        Middleware\CacheMiddleware::class => [Factory\CacheFactory::VIEWS_CACHE],
        Middleware\LanguageMiddleware::class => ['translator'],
    ],

];
