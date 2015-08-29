<?php
use Interop\Container\ContainerInterface;
use Zend\Expressive\Application;

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

// Setup autoloading
require 'vendor/autoload.php';

/** @var ContainerInterface $container */
$container = include 'config/container.php';
/** @var Application $app */
$app = $container->get(Application::class);
$app->run();
