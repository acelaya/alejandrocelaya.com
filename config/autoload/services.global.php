<?php
use Acelaya\Website\Action\Factory\ActionAbstractFactory;
use Acelaya\Website\Factory\ApplicationFactory;
use Acelaya\Website\Factory\RendererFactory;
use Acelaya\Website\Factory\RouterFactory;
use Zend\Expressive\Application;

return [

    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class,
            'router' => RouterFactory::class,
            'renderer' => RendererFactory::class
        ],
        'abstract_factories' => [
            ActionAbstractFactory::class
        ]
    ]

];
