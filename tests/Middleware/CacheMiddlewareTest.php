<?php
namespace AcelayaTest\Website\Middleware;

use Acelaya\Website\Middleware\CacheMiddleware;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;

class CacheMiddlewareTest extends TestCase
{
    /**
     * @var CacheMiddleware
     */
    protected $middleware;
    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    public function setUp()
    {
        $this->cache = new ArrayCache();
        $this->request = new ServerRequest([], [], '/foo');
        $this->router = $this->prophesize(RouterInterface::class);
        $this->router->match($this->request)->willReturn(
            RouteResult::fromRoute(new Route('/home', function () {
                return new Response();
            }))
        );

        $this->middleware = new CacheMiddleware($this->cache, $this->router->reveal());
    }

    public function testWithNoCacheNextIsInvoked()
    {
        $response = new Response();
        $invoked = false;
        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate->process(Argument::any())->will(function () use (&$invoked, $response) {
            $invoked = true;
            return $response;
        });

        $returnedResponse = $this->middleware->process($this->request, $delegate->reveal());
        $this->assertSame($response, $returnedResponse);
        $this->assertTrue($invoked);
    }

    public function testWithCacheNextIsNotInvoked()
    {
        $response = new Response();
        $invoked = false;
        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate->process(Argument::any())->will(function () use (&$invoked, $response) {
            $invoked = true;
            return $response;
        });

        $this->cache->save('/foo', 'some content');
        $this->middleware->process($this->request, $delegate->reveal());
        $this->assertFalse($invoked);
    }

    public function testWithNoCacheResponseIsCached()
    {
        $response = new Response();
        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate->process(Argument::any())->willReturn($response);

        $route = new Route('/home', function ($req) {
            return new Response();
        }, ['GET'], 'home');
        $this->router->match($this->request)->willReturn(RouteResult::fromRoute($route, ['cacheable' => true]));

        $this->assertFalse($this->cache->contains('/foo'));
        $this->middleware->process($this->request, $delegate->reveal());
        $this->assertTrue($this->cache->contains('/foo'));
    }
}
