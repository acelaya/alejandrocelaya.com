<?php

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Application;

// Set error reporting
if (getenv('APP_ENV') === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

/** @var ContainerInterface $container */
$container = include __DIR__ . '/../config/container.php';
$app = $container->get(Application::class)->run($container->get(ServerRequestInterface::class));
