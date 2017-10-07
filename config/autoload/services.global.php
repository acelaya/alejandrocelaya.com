<?php
declare(strict_types=1);

use Acelaya\Expressive\Factory\SlimRouterFactory;
use Acelaya\Website\Action\Contact;
use Acelaya\Website\Action\Template;
use Acelaya\Website\Console;
use Acelaya\Website\Factory;
use Acelaya\Website\Feed;
use Acelaya\Website\Feed\Template\Extension\BlogExtension;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Middleware\CacheMiddleware;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Acelaya\Website\Options\Factory\MailOptionsFactory;
use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service;
use Acelaya\Website\Template\Extension;
use Acelaya\ZsmAnnotatedServices\Factory\V3\AnnotatedFactory;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ServerRequestInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Component\Console as Symfony;
use Zend\Expressive;
use Zend\Expressive\Container;
use Zend\Expressive\Helper;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Stratigility\Middleware\ErrorHandler;

return [

    'service_manager' => [
        'factories' => [
            Expressive\Application::class => Container\ApplicationFactory::class,

            // Actions
            Contact::class => AnnotatedFactory::class,
            Template::class => AnnotatedFactory::class,

            // Services
            Expressive\Template\TemplateRendererInterface::class => Expressive\Plates\PlatesRendererFactory::class,
            Expressive\Router\RouterInterface::class => SlimRouterFactory::class,
            ErrorHandler::class => Expressive\Container\ErrorHandlerFactory::class,
            Expressive\Middleware\ErrorResponseGenerator::class => Container\ErrorResponseGeneratorFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
            Helper\ServerUrlHelper::class => InvokableFactory::class,

            ServerRequestInterface::class => Factory\RequestFactory::class,
            \Swift_Mailer::class => Factory\SwiftMailerFactory::class,
            Translator::class => Factory\TranslatorFactory::class,
            ReCaptcha::class => Factory\RecaptchaFactory::class,
            Service\RouteAssembler::class => AnnotatedFactory::class,
            Service\ContactService::class => AnnotatedFactory::class,
            ContactFilter::class => AnnotatedFactory::class,
            Feed\GuzzleClient::class => InvokableFactory::class,
            Feed\Service\BlogFeedConsumer::class => AnnotatedFactory::class,

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
        ],

        'abstract_factories' => [
            Factory\DotNotationConfigAbstractFactory::class,
        ],
    ],

    ConfigAbstractFactory::class => [
        Extension\TranslatorExtension::class => ['translator'],
        Extension\UrlExtension::class => [Service\RouteAssembler::class],
        Extension\NavigationExtension::class => ['translator', Service\RouteAssembler::class, 'config.navigation'],
        Extension\RecaptchaExtension::class => ['config.recaptcha'],
        BlogExtension::class => [Cache::class, Feed\BlogOptions::class],
    ],

];
