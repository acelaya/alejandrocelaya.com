<?php
use Acelaya\Website\Action\Template;
use Zend\Stdlib\ArrayUtils;

return [

    'routes' => [

        [
            'name' => 'home',
            'path' => '/[:lang/]',
            'allowed_methods' => ['GET'],
            'middleware' => Template::class,
            'options' => [
                'constraints' => [
                    'lang' => 'en|es'
                ],
                'defaults' => [
                    'template' => 'home.html.twig',
                    'lang' => 'en'
                ],
                'skippable' => [
                    'lang' => true
                ]
            ]
        ],

        [
            'name' => 'skills',
            'path' => '/[:lang/]skills/',
            'allowed_methods' => ['GET'],
            'middleware' => Template::class,
            'options' => [
                'constraints' => [
                    'lang' => 'en|es'
                ],
                'defaults' => [
                    'template' => 'skills.html.twig',
                    'lang' => 'en'
                ],
                'skippable' => [
                    'lang' => true
                ]
            ]
        ],

        [
            'name' => 'projects',
            'path' => '/[:lang/]projects/',
            'allowed_methods' => ['GET'],
            'middleware' => Template::class,
            'options' => [
                'constraints' => [
                    'lang' => 'en|es'
                ],
                'defaults' => [
                    'template' => 'projects.html.twig',
                    'lang' => 'en'
                ],
                'skippable' => [
                    'lang' => true
                ]
            ]
        ],
    ]

];
