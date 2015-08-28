<?php
use Acelaya\Website\Factory\ApplicationFactory;
use Zend\Expressive\Application;

return [

    'service_manager' => [
        'factories' => [
            Application::class => ApplicationFactory::class
        ]
    ]

];
