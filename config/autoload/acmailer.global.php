<?php
declare(strict_types=1);

return [

    'acmailer_options' => [
        'emails' => [
            'contact' => [
                'from' => getenv('EMAIL_USERNAME'),
                //'reply_to' => '',
                'to' => [getenv('EMAIL_USERNAME')],
                'subject' => 'Alejandro Celaya | Contact form',
                'template' => 'Acelaya::emails/contact',
            ],
        ],

        'mail_services' => [
            'default' => [
                'transport' => 'smtp',
                'transport_options' => [
                    'host' => 'smtp.gmail.com',
                    'port' => 587,
                    'connection_class' => 'login',
                    'connection_config' => [
                        'username' => getenv('EMAIL_USERNAME'),
                        'password' => getenv('EMAIL_PASSWORD'),
                        'ssl' => 'tls',
                    ],
                ],
            ],
        ],
    ],

];
