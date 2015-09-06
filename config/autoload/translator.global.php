<?php
return [

    'translator' => [
        'locale' => 'en',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../../data/language',
                'pattern' => '%s.mo'
            ]
        ]
    ]

];
