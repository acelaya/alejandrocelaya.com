<?php
use Acelaya\Website\Action\Template;
use Zend\Stdlib\ArrayUtils;

$home = [
    'name' => 'home',
    'path' => '/',
    'allowed_methods' => ['GET'],
    'middleware' => Template::class,
    'options' => [
        'values' => [
            'lang' => 'en',
            'template' => 'home.html.twig'
        ],
    ]
];

return [

    'routes' => [
        $home,
        ArrayUtils::merge($home, [
            'name' => 'lang',
            'path' => '/{lang}',
        ]),
    ]

];
