<?php
declare(strict_types=1);

namespace Acelaya\Website;

use Acelaya\Expressive as AcelayaRouter;
use AcMailer;
use Zend\ConfigAggregator;
use Zend\Expressive;

return (new ConfigAggregator\ConfigAggregator([
    Expressive\ConfigProvider::class,
    Expressive\Router\ConfigProvider::class,
    Expressive\Plates\ConfigProvider::class,
    Expressive\Helper\ConfigProvider::class,
    Expressive\Swoole\ConfigProvider::class,
    AcelayaRouter\ConfigProvider::class,
    AcMailer\ConfigProvider::class,
    new ConfigAggregator\PhpFileProvider(__DIR__ . '/autoload/{{,*.}global,{,*.}local}.php'),
], __DIR__ . '/../data/cache/config_cache.php'))->getMergedConfig();
