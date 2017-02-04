<?php
use Zend\Config\Factory;
use Zend\Stdlib\Glob;

$env = getenv('APP_ENV') ?: 'dev';
$mergedConfigFile = __DIR__ . '/../data/cache/config_cache.php';

// If in production and merged config exists, return it
if ($env === 'pro' && is_file($mergedConfigFile)) {
    return include $mergedConfigFile;
}

// Merge configuration files
$mergedConfig = Factory::fromFiles(Glob::glob(__DIR__ . '/autoload/{,*.}{global,local}.php', Glob::GLOB_BRACE));

// If in production, cache merged config
if ($env === 'pro') {
    Factory::toFile($mergedConfigFile, $mergedConfig);
}

// Finally, Return the merged configuration
return $mergedConfig;
