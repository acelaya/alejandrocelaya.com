<?php
use Acelaya\Website\Action\Factory\ActionAbstractFactory;
use Acelaya\Website\Factory\RendererFactory;
use Acelaya\Website\Factory\TranslatorFactory;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\I18n\Translator\Translator;

return [

    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class,
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
