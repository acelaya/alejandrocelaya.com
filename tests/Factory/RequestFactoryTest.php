<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\RequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\ServiceManager\ServiceManager;

class RequestFactoryTest extends TestCase
{
    /**
     * @var Requestfactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new RequestFactory();
    }

    public function testInvoke()
    {
        $instance = $this->factory->__invoke(new ServiceManager(), '');
        $this->assertInstanceOf(ServerRequestInterface::class, $instance);
    }
}
