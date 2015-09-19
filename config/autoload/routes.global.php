<?php
use Acelaya\Website\Action\Contact;
use Acelaya\Website\Action\Template;
use Zend\Stdlib\ArrayUtils;

$home = [
    'name' => 'home',
    'path' => '(/:lang)/',
    'allowed_methods' => ['GET'],
    'middleware' => Template::class,
    'options' => [
        'conditions' => [
            'lang' => 'en|es'
        ],
        'defaults' => [
            'template' => 'home.html.twig',
            'lang' => 'en',
            'cacheable' => true
        ],
    ]
];

return [
    'routes' => [
        $home,
        ArrayUtils::merge($home, [
            'name' => 'skills',
            'path' => '(/:lang)/skills/',
            'options' => [
                'defaults' => [
                    'template' => 'skills.html.twig',
                ],
            ]
        ]),
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
            'middleware' => Contact::class,
            'options' => [
                'defaults' => [
                    'template' => 'contact.html.twig',
                    'cacheable' => false
                ],
            ]
        ]),
    ]
];
