<?php
declare(strict_types=1);
return [

    'mail' => [
        'smtp' => [
            'server' => 'smtp.gmail.com',
            'ssl' => 'tls',
            'port' => 587,
            'username' => getenv('EMAIL_USERNAME'),
            'password' => getenv('EMAIL_PASSWORD'),
        ],
        'from' => getenv('EMAIL_USERNAME'),
        'to' => getenv('EMAIL_USERNAME'),
        'subject' => 'Alejandro Celaya | Contact form',
    ],

];
