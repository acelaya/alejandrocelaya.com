<?php
use Zend\ServiceManager\ServiceManager;

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

// Setup autoloading
require 'vendor/autoload.php';

// Create a ServiceManager from service_manager config and register the merged config as a service
return (function () {
    $config = include __DIR__ . '/config.php';
    $sm = new ServiceManager($config['service_manager'] ?? []);
    $sm->setService('config', $config);
    return $sm;
})();
