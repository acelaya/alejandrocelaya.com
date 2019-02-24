<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Middleware;

use Acelaya\Website\Middleware\CacheMiddleware;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouteResult;
use function Zend\Stratigility\middleware;

class CacheMiddlewareTest extends TestCase
{
    /** @var CacheMiddleware */
    protected $middleware;
    /** @var Cache */
    protected $cache;
    /** @var ServerRequestInterface */
    protected $request;

    protected function setUp(): void
    {
        $this->cache = new ArrayCache();
        $this->request = (new ServerRequest([], [], '/foo'))->withAttribute(
            RouteResult::class,
            RouteResult::fromRoute(new Route('/home', middleware(function () {
                return new Response();
            })))
        );

        $this->middleware = new CacheMiddleware($this->cache);
    }

    public function testWithNoCacheNextIsInvoked()
    {
        $response = new Response();
        $invoked = false;
        $delegate = $this->prophesize(RequestHandlerInterface::class);
        $delegate->handle(Argument::any())->will(function () use (&$invoked, $response) {
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
        $delegate = $this->prophesize(RequestHandlerInterface::class);
        $delegate->handle(Argument::any())->will(function () use (&$invoked, $response) {
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
        $delegate = $this->prophesize(RequestHandlerInterface::class);
        $delegate->handle(Argument::any())->willReturn($response);

        $route = new Route('/home', middleware(function ($req) {
            return new Response();
        }), ['GET'], 'home');

        $this->assertFalse($this->cache->contains('/foo'));
        $this->middleware->process($this->request->withAttribute(
            RouteResult::class,
            RouteResult::fromRoute($route, ['cacheable' => true])
        ), $delegate->reveal());
        $this->assertTrue($this->cache->contains('/foo'));
    }

    /**
     * @test
     */
    public function cacheIsMypassedIfQueryParamIsOProvided()
    {
        $response = new Response();
        $delegate = $this->prophesize(RequestHandlerInterface::class);
        $delegate->handle(Argument::any())->willReturn($response)->shouldBeCalledTimes(1);

        $request = ServerRequestFactory::fromGlobals()->withQueryParams(['bypass-cache' => true]);

        $this->middleware->process($request, $delegate->reveal());
    }
}
