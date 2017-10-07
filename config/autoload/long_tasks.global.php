<?php
declare(strict_types=1);
use Acelaya\Website\Console\Task\BlogFeedConsumerTask;

return [

    'long_tasks' => [
        'tasks' => [
            BlogFeedConsumerTask::class,
        ],
    ],

];
