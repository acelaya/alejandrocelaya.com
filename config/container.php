<?php
declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Zend\ServiceManager\ServiceManager;

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

// Setup autoloading
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
if (class_exists(Dotenv::class)) {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__ . '/../.env');
}

// Create a ServiceManager from service_manager config and register the merged config as a service
return (function () {
    $config = include __DIR__ . '/config.php';
    $sm = new ServiceManager($config['dependencies'] ?? []);
    $sm->setService('config', $config);
    return $sm;
})();
