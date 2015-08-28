<?php
namespace Acelaya\Website\Factory;

use Aura\Router\Generator;
use Aura\Router\RouteCollection;
use Aura\Router\RouteFactory;
use Aura\Router\Router;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\Aura as AuraRouter;
use Zend\Expressive\Router\Route;

class RouterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        // TODO cache routes
        $router = new AuraRouter(new Router(new RouteCollection(new RouteFactory()), new Generator()));
        $routesConfig = $container->get('config')['routes'];

        // Impprove this allowing inheritance by recursivity
        foreach ($routesConfig as $name => $routeConfig) {
            $router->addRoute(new Route(
                $routeConfig['path'],
                $routeConfig['middleware'],
                $routeConfig['methods'],
                $name
            ));
        }

        return $router;
    }
}
