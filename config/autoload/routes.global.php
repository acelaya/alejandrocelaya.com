<?php
use Acelaya\Website\Action\Home;

return [

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'allowed_methods' => ['GET'],
            'middleware' => Home::class,
            'options' => [
                'values' => [
                    'lang' => 'en'
                ],
            ]
        ],
        [
            'name' => 'lang',
            'path' => '/{lang}',
            'allowed_methods' => ['GET'],
            'middleware' => Home::class,
            'options' => [
                'values' => [
                    'lang' => 'en'
                ],
                'tokens' => [
                    'lang' => 'en|es'
                ]
            ]
        ]
    ]

];
