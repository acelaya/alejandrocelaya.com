<?php
use Acelaya\Website\Action;
use Zend\Stdlib\ArrayUtils;

$home = [
    'name' => 'home',
    'path' => '(/:lang)/',
    'allowed_methods' => ['GET'],
    'middleware' => Action\Template::class,
    'options' => [
        'conditions' => [
            'lang' => 'en|es'
        ],
        'defaults' => [
            'template' => 'home',
            'cacheable' => true
        ],
    ]
];

return [
    'routes' => [
        $home,
        ArrayUtils::merge($home, [
            'name' => 'projects',
            'path' => '(/:lang)/projects/',
            'options' => [
                'defaults' => [
                    'template' => 'projects.html.twig',
                ],
            ]
        ]),
        ArrayUtils::merge($home, [
            'name' => 'contact',
            'path' => '(/:lang)/contact/',
            'allowed_methods' => ['POST'],
            'middleware' => Action\Contact::class,
            'options' => [
                'defaults' => [
                    'template' => 'contact.html.twig',
                    'cacheable' => false
                ],
            ]
        ]),

        // Redirect old sitemap to new one
        [
            'name' => 'sitemap-redirect',
            'path' => '/Sitemap.xml',
            'allowed_methods' => ['GET'],
            'middleware' => Action\Redirect::class,
            'options' => [
                'defaults' => [
                    'to' => '/sitemap.xml'
                ],
            ],
        ],
    ],
];
