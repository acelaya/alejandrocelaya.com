<?php
namespace AcelayaTest\Website\Middleware;

use Acelaya\Website\Action\Template;
use Acelaya\Website\Middleware\CacheMiddleware;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
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
        $this->router->match($this->request)->willReturn(RouteResult::fromRouteMatch('home', function ($req, $resp) {
            return $resp;
        }, []));

        $this->middleware = new CacheMiddleware($this->cache, $this->router->reveal());
    }

    public function testWithNoCacheNextIsInvoked()
    {
        $response = new Response();
        $invoked = false;
        $next = function ($req, $resp) use (&$invoked) {
            $invoked = true;
            return $resp;
        };

        $returnedResponse = $this->middleware->__invoke($this->request, $response, $next);
        $this->assertSame($response, $returnedResponse);
        $this->assertTrue($invoked);
    }

    public function testWithCacheNextIsNotInvoked()
    {
        $response = new Response();
        $invoked = false;
        $next = function ($req, $resp) use (&$invoked) {
            $invoked = true;
            return $resp;
        };

        $this->cache->save('/foo', 'some content');
        $this->middleware->__invoke($this->request, $response, $next);
        $this->assertFalse($invoked);
    }
}
