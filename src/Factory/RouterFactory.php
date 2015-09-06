<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Acelaya\Website\Router\ZF2;

class RouterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return new ZF2();
    }
}
