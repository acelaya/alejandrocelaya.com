<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\Aura;

class RouterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return new Aura((new \Aura\Router\RouterFactory())->newInstance());
    }
}
