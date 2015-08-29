<?php
use Acelaya\Website\Action\Home;

return [

    'routes' => [
        'home' => [
            'name' => 'home',
            'path' => '/',
            'methods' => ['GET'],
            'allowed_methods' => ['GET'],
            'middleware' => Home::class
        ]
    ]

];
