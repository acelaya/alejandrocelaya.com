<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;

interface FactoryInterface
{
    public function __invoke(ContainerInterface $container);
}
