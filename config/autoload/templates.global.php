<?php
declare(strict_types=1);

use Acelaya\Website\Feed\Template\Extension\BlogExtension;
use Acelaya\Website\Template\Extension;

return [

    'templates' => [
        'extension' => 'phtml',
        'paths' => [
            'Acelaya' => __DIR__ . '/../../templates',
        ],
    ],

    'plates' => [
        'extensions' => [
            Extension\TranslatorExtension::class,
            Extension\UrlExtension::class,
            Extension\NavigationExtension::class,
            Extension\RecaptchaExtension::class,
            BlogExtension::class,
        ],
    ],

];
