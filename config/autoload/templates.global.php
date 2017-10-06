<?php
declare(strict_types=1);

use Acelaya\Website\Template\Extension\TranslatorExtension;

return [

    'templates' => [
        'extension' => 'phtml',
        'paths' => [
            'Acelaya' => __DIR__ . '/../../templates',
        ],
    ],

    'plates' => [
        'extensions' => [
            TranslatorExtension::class,
        ],
    ],

];
