<?php
declare(strict_types=1);
return [

    'recaptcha' => [
        'public_key' => getenv('RECAPTCHA_PUBLIC'),
        'private_key' => getenv('RECAPTCHA_PRIVATE'),
    ],

];
