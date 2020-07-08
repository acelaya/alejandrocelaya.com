<?php
declare(strict_types=1);

use Acelaya\Website\Action;
use Zend\Stdlib\ArrayUtils;

$home = [
    'name' => 'home',
    'path' => '(/:lang)/',
    'allowed_methods' => ['GET'],
    'middleware' => Action\Template::class,
    'options' => [
        'conditions' => [
            'lang' => 'en|es',
        ],
        'defaults' => [
            'template' => 'Acelaya::home',
            'cacheable' => true,
        ],
    ],
];

return [
    'routes' => [
        $home,
        ArrayUtils::merge($home, [
            'name' => 'projects',
            'path' => '(/:lang)/projects/',
            'options' => [
                'defaults' => [
                    'template' => 'Acelaya::projects',
                ],
            ],
        ]),

        // Redirect old sitemap to new one
        [
            'name' => 'sitemap-redirect',
            'path' => '/Sitemap.xml',
            'allowed_methods' => ['GET'],
            'middleware' => Action\Redirect::class,
            'options' => [
                'defaults' => [
                    'to' => '/sitemap.xml',
                ],
            ],
        ],

        // Redirect contact page to home
        [
            'name' => 'contact-redirect',
            'path' => '/contact/',
            'allowed_methods' => ['GET'],
            'middleware' => Action\Redirect::class,
            'options' => [
                'defaults' => [
                    'to' => '/',
                ],
            ],
        ],
        [
            'name' => 'contact-redirect-en',
            'path' => '/en/contact/',
            'allowed_methods' => ['GET'],
            'middleware' => Action\Redirect::class,
            'options' => [
                'defaults' => [
                    'to' => '/en/',
                ],
            ],
        ],
        [
            'name' => 'contact-redirect-es',
            'path' => '/es/contact/',
            'allowed_methods' => ['GET'],
            'middleware' => Action\Redirect::class,
            'options' => [
                'defaults' => [
                    'to' => '/es/',
                ],
            ],
        ],
    ],
];
