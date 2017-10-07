<?php
declare(strict_types=1);

namespace Acelaya\Website\Factory;

use Doctrine\Common\Cache;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Predis\Client;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class CacheFactory implements FactoryInterface
{
    const FEED_CACHE = 'Acelaya\Website\FeedCache';
    const VIEWS_CACHE = 'Acelaya\Website\ViewsCache';

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return Cache\Cache|object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Cache\Cache
    {
        $config = $container->get('config')['cache'] ?? [];
        $isDev = $container->get('config')['debug'] ?? true;
        $adapter = $this->getAdapter($config, $requestedName, (bool) $isDev);
        $adapter->setNamespace($config['namespaces'][$requestedName] ?? 'www.alejandrocelaya.com');

        return $adapter;
    }

    /**
     * @param array $cacheConfig
     * @param $requestedName
     * @param bool $isDev
     * @return Cache\CacheProvider
     */
    protected function getAdapter(array $cacheConfig, $requestedName, bool $isDev): Cache\CacheProvider
    {
        if (! $isDev || $requestedName === self::FEED_CACHE) {
            $client = new Client($cacheConfig['redis']);
            return new Cache\PredisCache($client);
        }

        return new Cache\ArrayCache();
    }
}
