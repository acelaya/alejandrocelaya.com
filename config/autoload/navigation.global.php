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
            ]
        ],
        'lang_menu' => [
            [
                'label'    => 'Es',
                'params'   => [
                    'lang' => 'es'
                ]
            ],
            [
                'label'    => 'En',
                'params'   => [
                    'lang' => 'en'
                ]
            ],
        ]
    ]

];
