<?php
namespace Acelaya\Website\Middleware\Factory;

use Acelaya\Website\Middleware\CacheMiddleware;
use Doctrine\Common\Cache\Cache;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Expressive\Router\RouterInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class CacheMiddlewareFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Cache $cache */
        $cache = $container->get(Cache::class);
        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);
        return new CacheMiddleware($cache, $router);
    }
}
