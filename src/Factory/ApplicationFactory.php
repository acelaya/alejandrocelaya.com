<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Zend\Expressive\AppFactory;

class ApplicationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return AppFactory::create(
            $container,
            $container->get('router')
        );
    }
}
