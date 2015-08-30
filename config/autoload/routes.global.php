<?php
use Acelaya\Website\Action\Home;

return [

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'allowed_methods' => ['GET'],
            'middleware' => Home::class
        ]
    ]

];
