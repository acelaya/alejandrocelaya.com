<?php
namespace Acelaya\Website\Service\Factory;

use Acelaya\Website\Factory\FactoryInterface;
use Acelaya\Website\Service\RouteAssembler;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouterInterface;

class RouteAssemblerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return new RouteAssembler(
            $container->get(RouterInterface::class),
            $container->get(ServerRequestInterface::class)
        );
    }
}
