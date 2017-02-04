<?php
namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\CacheFactory;
use Doctrine\Common\Cache;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class CacheFactoryTest extends TestCase
{
    /**
     * @var CacheFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new CacheFactory();
    }

    public function testCacheInProduction()
    {
        putenv('APP_ENV=pro');
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [
                'cache' => [
                    'redis' => [],
                ],
            ],
        ]]), '');
        $this->assertInstanceOf(Cache\PredisCache::class, $instance);
    }

    public function testCachInDevelopment()
    {
        putenv('APP_ENV=dev');
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [],
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
