<?php
use Acelaya\Website\I18n\FakeTranslator;

return [

    'navigation' => [
        'menu' => [
            [
                'label' => FakeTranslator::translate('Home'),
                'route' => 'home',
            ],
            [
                'label' => FakeTranslator::translate('Skills'),
                'route' => 'skills',
            ],
            [
                'label' => FakeTranslator::translate('Projects'),
                'route' => 'projects',
            ],
            [
                'label' => FakeTranslator::translate('Contact'),
                'route' => 'contact',
            ],
            [
                'label'     => FakeTranslator::translate('Blog'),
                'uri'       => 'https://blog.alejandrocelaya.com',
                'target'    => true,
            ],
        ],
        'lang_menu' => [
            [
                'label'    => 'Es',
                'params'   => [
                    'lang' => 'es',
                ],
            ],
            [
                'label'    => 'En',
                'params'   => [
                    'lang' => 'en',
                ],
            ],
        ],
        'social_menu' => [
            [
                'uri' => 'https://www.linkedin.com/in/alejandro-celaya-alastru%C3%A9-25755263',
                'icon' => 'icon-linkedin',
            ],
            [
                'uri' => 'https://www.zend.com/en/yellow-pages/ZEND021590',
                'icon' => 'icon-code',
            ],
            [
                'uri' => 'https://github.com/acelaya',
                'icon' => 'icon-github',
            ],
            [
                'uri' => 'https://twitter.com/acelayaa',
                'icon' => 'icon-twitter',
            ],
            [
                'uri' => 'https://plus.google.com/+AlejandroCelaya?rel=author',
                'icon' => 'icon-google-plus',
            ],
        ],
    ],

];
