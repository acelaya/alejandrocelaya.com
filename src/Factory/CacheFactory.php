<?php
namespace Acelaya\Website\Factory;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Interop\Container\ContainerInterface;

class CacheFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return getenv('APP_ENV') === 'pro'
            ? new FilesystemCache(__DIR__ . '/../../data/cache')
            : new ArrayCache();
    }
}
