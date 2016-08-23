<?php
namespace Acelaya\Website\Factory;

use Doctrine\Common\Cache;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class CacheFactory implements FactoryInterface
{
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
        $adapter = $this->getAdapter();
        $adapter->setNamespace('https://www.alejandrocelaya.com');

        return $adapter;
    }

    /**
     * @return Cache\CacheProvider
     */
    protected function getAdapter(): Cache\CacheProvider
    {
//        return getenv('APP_ENV') === 'pro' ? new Cache\ApcuCache() : new Cache\ArrayCache();
        return getenv('APP_ENV') === 'pro' ? new Cache\FilesystemCache('data/cache/common') : new Cache\ArrayCache();
    }
}
