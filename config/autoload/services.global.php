<?php
use Acelaya\Website\Action\Factory\ActionAbstractFactory;
use Acelaya\Website\Factory\ApplicationFactory;
use Acelaya\Website\Factory\RendererFactory;
use Acelaya\Website\Factory\RouterFactory;
use Acelaya\Website\Factory\TranslatorFactory;
use Zend\Expressive\Application;
use Zend\I18n\Translator\Translator;

return [

    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class,
            'router' => RouterFactory::class,
            'renderer' => RendererFactory::class,
            Translator::class => TranslatorFactory::class
        ],
        'abstract_factories' => [
            ActionAbstractFactory::class
        ],
        'aliases' => [
            'translator' => Translator::class
        ]
    ]

];
