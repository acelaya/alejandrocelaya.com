<?php
namespace AcelayaTest\Website\Feed\Service;

use Acelaya\Website\Feed\Service\FeedCacheFactory;
use Doctrine\Common\Cache\FilesystemCache;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class FeedCacheFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function serviceIsCreated()
    {
        $factory = new FeedCacheFactory();
        $instance = $factory->__invoke(new ServiceManager(), '');
        $this->assertInstanceOf(FilesystemCache::class, $instance);
    }
}
