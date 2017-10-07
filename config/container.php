<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use Zend\ServiceManager\ServiceManager;

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

// Setup autoloading
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
if (class_exists(Dotenv::class)) {
    $dotenv = new Dotenv(__DIR__ . '/..');
    $dotenv->load();
    $dotenv->required('APP_ENV')->allowedValues(['pro', 'dev']);
}

// Create a ServiceManager from service_manager config and register the merged config as a service
return (function () {
    $config = include __DIR__ . '/config.php';
    $sm = new ServiceManager($config['service_manager'] ?? []);
    $sm->setService('config', $config);
    return $sm;
})();
