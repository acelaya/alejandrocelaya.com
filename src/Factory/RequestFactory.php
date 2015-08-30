<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Zend\Diactoros\ServerRequestFactory;

class RequestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return ServerRequestFactory::fromGlobals();
    }
}
