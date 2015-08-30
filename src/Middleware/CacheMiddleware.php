<?php
namespace Acelaya\Website\Middleware;

use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Router\RouterInterface;
use Zend\Stratigility\MiddlewareInterface;

class CacheMiddleware implements MiddlewareInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var Cache
     */
    protected $cache;

    public function __construct(Cache $cache, RouterInterface $router)
    {
        $this->cache = $cache;
        $this->router = $router;
    }

    /**
     * Process an incoming request and/or response.
     *
     * Accepts a server-side request and a response instance, and does
     * something with them.
     *
     * If the response is not complete and/or further processing would not
     * interfere with the work done in the middleware, or if the middleware
     * wants to delegate to another process, it can use the `$out` callable
     * if present.
     *
     * If the middleware does not return a value, execution of the current
     * request is considered complete, and the response instance provided will
     * be considered the response to return.
     *
     * Alternately, the middleware may return a response instance.
     *
     * Often, middleware will `return $out();`, with the assumption that a
     * later middleware will return a response.
     *
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $currentRoute = $this->router->match($request);
        $currentRoutePath = $request->getUri()->getPath();

        // If current route is a success route and it has been previously cached, write cached content and retur
        if ($currentRoute->isSuccess() && $this->cache->contains($currentRoutePath)) {
            $response->getBody()->write($this->cache->fetch($currentRoutePath));
            return $response;
        }

        return $out($request, $response);
    }
}
