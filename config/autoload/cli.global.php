<?php
declare(strict_types=1);
use Acelaya\Website\Console\Command;

return [

    'cli' => [
        'commands' => [
            Command\LongTasksCommand::class,
        ],
    ],

];
