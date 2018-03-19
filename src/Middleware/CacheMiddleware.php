<?php
declare(strict_types=1);

namespace Acelaya\Website\Middleware;

use Doctrine\Common\Cache\Cache;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

class CacheMiddleware implements MiddlewareInterface, StatusCodeInterface
{
    /**
     * @var Cache
     */
    protected $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param Request $request
     * @param RequestHandlerInterface $handler
     *
     * @return Response
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        // Bypass cache if provided bypass-cache param
        if (array_key_exists('bypass-cache', $request->getQueryParams())) {
            return $handler->handle($request);
        }

        /** @var RouteResult $currentRoute */
        $currentRoute = $request->getAttribute(RouteResult::class);
        $currentRoutePath = $request->getUri()->getPath();

        // If current route is a success route and it has been previously cached, write cached content and return
        if ($currentRoute->isSuccess() && $this->cache->contains($currentRoutePath)) {
            $resp = (new \Zend\Diactoros\Response())->withHeader('content-type', 'text/html');
            $resp->getBody()->write($this->cache->fetch($currentRoutePath));
            return $resp;
        }

        // If the response is not cached, process the next middleware and get its response, then cache it
        $resp = $handler->handle($request);
        if ($this->isResponseCacheable($resp, $currentRoute->getMatchedParams())) {
            $this->cache->save($currentRoutePath, (string) $resp->getBody());
        }

        return $resp;
    }

    /**
     * Tells if a response is cacheable based on its status and current route params
     *
     * @param Response $resp
     * @param array $routeParams
     * @return bool
     */
    protected function isResponseCacheable(Response $resp, array $routeParams = []): bool
    {
        $isCacheable = (bool) ($routeParams['cacheable'] ?? false);
        return $resp->getStatusCode() === self::STATUS_OK && $isCacheable;
    }
}
