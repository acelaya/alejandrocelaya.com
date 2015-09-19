<?php
namespace AcelayaTest\Website\Router;

use Acelaya\Website\Router\SlimRouter;
use PHPUnit_Framework_TestCase as TestCase;
use Slim\Router;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Expressive\Router\Route;

class SlimRouterTest extends TestCase
{
    /**
     * @var SlimRouter
     */
    protected $router;
    /**
     * @var Router
     */
    protected $slimRouter;

    public function setUp()
    {
        $this->slimRouter = new Router();
        $this->router = new SlimRouter($this->slimRouter);
    }

    public function testAddRoute()
    {
        $this->router->addRoute(new Route('/foo(/:bar)', 'Home', ['GET', 'POST'], 'home'));
        $this->assertCount(1, $this->slimRouter->getNamedRoutes());

        /** @var \Slim\Route $route */
        $route = $this->slimRouter->getMatchedRoutes('GET', '/foo/baz')[0];
        $this->assertEquals('/foo(/:bar)', $route->getPattern());
        $this->assertEquals('home', $route->getName());
        $this->assertEquals('Home', $route->getParams()['middleware']);
    }

    public function testAddRouteWithOptions()
    {
        $route = new Route('/foo/:bar', 'Home', ['GET', 'POST'], 'home');
        $route->setOptions([
            'conditions' => [
                'bar' => 'es|en'
            ],
            'defaults' => [
                'bar' => 'en'
            ]
        ]);
        $this->router->addRoute($route);

        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('GET', '/foo/es'));
        $this->assertCount(0, $this->slimRouter->getMatchedRoutes('GET', '/foo/baz', true));
    }

    public function testAddRouteWithAnyMethod()
    {
        $route = new Route('/foo/bar', 'Home', Route::HTTP_METHOD_ANY, 'home');
        $this->router->addRoute($route);

        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('GET', '/foo/bar'));
        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('POST', '/foo/bar'));
        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('PUT', '/foo/bar'));
        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('DELETE', '/foo/bar'));
        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('PATCH', '/foo/bar'));
        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('OPTIONS', '/foo/bar'));
        $this->assertCount(1, $this->slimRouter->getMatchedRoutes('HEAD', '/foo/bar'));
    }

    public function testDummyCallable()
    {
        $this->assertNull($this->router->dummyCallable());
    }

    public function testGenerateUrl()
    {
        $route = new Route('/foo(/:bar)', 'Home', ['GET', 'POST'], 'home');
        $this->router->addRoute($route);

        $this->assertEquals('/foo', $this->router->generateUri('home'));
        $this->assertEquals('/foo/baz', $this->router->generateUri('home', ['bar' => 'baz']));
    }

    /**
     * @expectedException \Zend\Expressive\Exception\RuntimeException
     */
    public function testGenerateUrlWithInvalidName()
    {
        $route = new Route('/foo(/:bar)', 'Home', ['GET', 'POST'], 'home');
        $this->router->addRoute($route);
        $this->router->generateUri('invalidName');
    }

    public function testMatchInvalidRequest()
    {
        $result = $this->router->match(ServerRequestFactory::fromGlobals());
        $this->assertTrue($result->isFailure());
    }

    public function testMatchValidRequest()
    {
        $this->router->addRoute(new Route('/foo(/:bar)', 'Home', ['GET', 'POST'], 'home'));
        $this->assertCount(1, $this->slimRouter->getNamedRoutes());
        $result = $this->router->match(new ServerRequest([], [], '/foo/bar', 'POST'));
        $this->assertTrue($result->isSuccess());
    }
}
