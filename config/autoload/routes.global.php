<?php
use Acelaya\Website\Action\Home;

return [

    'routes' => [
        'home' => [
            'path' => '/',
            'methods' => ['GET'],
            'middleware' => Home::class
        ]
    ]

];
