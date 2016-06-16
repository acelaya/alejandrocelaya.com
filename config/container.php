<?php
use Zend\ServiceManager\ServiceManager;

// Create a ServiceManager from service_manager config and register the merged config as a service
$config = include __DIR__ . '/config.php';
$config['service_manager']['services']['config'] = $config;
return new ServiceManager($config['service_manager'] ?? []);
