<?php
use Acelaya\Website\Action\Template;
use Zend\Stdlib\ArrayUtils;

$home = [
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
];

return [

    'routes' => [

        $home,
        ArrayUtils::merge($home, [
            'name' => 'skills',
            'path' => '/[:lang/]skills/',
            'options' => [
                'defaults' => [
                    'template' => 'skills.html.twig',
                ],
            ]
        ]),
        ArrayUtils::merge($home, [
            'name' => 'projects',
            'path' => '/[:lang/]projects/',
            'options' => [
                'defaults' => [
                    'template' => 'projects.html.twig',
                ],
            ]
        ]),
        ArrayUtils::merge($home, [
            'name' => 'contact',
            'path' => '/[:lang/]contact/',
            'options' => [
                'defaults' => [
                    'template' => 'contact.html.twig',
                ],
            ]
        ]),
    ]

];
