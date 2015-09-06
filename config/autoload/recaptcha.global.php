<?php
return [

    'recaptcha' => [
        'public_key' => getenv('RECAPTCHA_PUBLIC'),
        'private_key' => getenv('RECAPTCHA_PRIVATE'),
    ]

];
