<?php
namespace Acelaya\Website\Middleware\Factory;

use Acelaya\Website\Factory\FactoryInterface;
use Acelaya\Website\Middleware\CacheMiddleware;
use Doctrine\Common\Cache\Cache;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class CacheMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var Cache $cache */
        $cache = $container->get(Cache::class);
        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);
        return new CacheMiddleware($cache, $router);
    }
}
