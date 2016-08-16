<?php
use Acelaya\Website\I18n\FakeTranslator;

return [

    'navigation' => [
        'menu' => [
            [
                'label' => FakeTranslator::translate('Skills'),
                'route' => 'skills',
                'icon'  => 'fa-tags',
            ],
            [
                'label' => FakeTranslator::translate('Projects'),
                'route' => 'projects',
                'icon'  => 'fa-cog',
            ],
            [
                'label' => FakeTranslator::translate('Contact'),
                'route' => 'contact',
                'icon'  => 'fa fa-envelope',
            ],
            [
                'label'     => FakeTranslator::translate('Blog'),
                'uri'       => 'https://blog.alejandrocelaya.com',
                'icon'      => 'fa-book',
                'target'    => true,
            ]
        ],
        'lang_menu' => [
            [
                'label'    => 'Español',
                'class'    => 'es',
                'params'   => [
                    'lang' => 'es'
                ]
            ],
            [
                'label'    => 'English',
                'class'    => 'en',
                'params'   => [
                    'lang' => 'en'
                ]
            ],
        ]
    ]

];
