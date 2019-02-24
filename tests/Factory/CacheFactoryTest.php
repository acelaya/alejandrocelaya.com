<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\CacheFactory;
use Doctrine\Common\Cache;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

class CacheFactoryTest extends TestCase
{
    /** @var CacheFactory */
    protected $factory;

    protected function setUp(): void
    {
        $this->factory = new CacheFactory();
    }

    public function testCacheInProduction()
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [
                'cache' => [
                    'redis' => [],
                ],
                'debug' => false,
            ],
        ]]), '');
        $this->assertInstanceOf(Cache\PredisCache::class, $instance);
    }

    public function testCachInDevelopment()
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [
                'debug' => true,
            ],
        ]]), '');
        $this->assertInstanceOf(Cache\ArrayCache::class, $instance);
    }

    public function testCacheWithNoDefinedEnvironmentIsConsideredDev()
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [],
        ]]), '');
        $this->assertInstanceOf(Cache\ArrayCache::class, $instance);
    }
}
