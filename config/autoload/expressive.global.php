<?php

use Acelaya\Website\Action\Template;

return [

    'zend-expressive' => [
        'error_handler' => [
            'template_404' => Template::NOT_FOUND_TEMPLATE,
            'template_error' => 'Acelaya::errors/500'
        ]
    ]

];
