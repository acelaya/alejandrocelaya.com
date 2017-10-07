<?php
declare(strict_types=1);
namespace Acelaya\Website\Middleware;

use Acelaya\Website\Factory\CacheFactory;
use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Doctrine\Common\Cache\Cache;
use Fig\Http\Message\StatusCodeInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Router\RouterInterface;

class CacheMiddleware implements MiddlewareInterface, StatusCodeInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * CacheMiddleware constructor.
     * @param Cache $cache
     * @param RouterInterface $router
     *
     * @Inject({CacheFactory::VIEWS_CACHE, RouterInterface::class})
     */
    public function __construct(Cache $cache, RouterInterface $router)
    {
        $this->cache = $cache;
        $this->router = $router;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param Request $request
     * @param DelegateInterface $delegate
     *
     * @return Response
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function process(Request $request, DelegateInterface $delegate)
    {
        $currentRoute = $this->router->match($request);
        $currentRoutePath = $request->getUri()->getPath();

        // If current route is a success route and it has been previously cached, write cached content and return
        if ($currentRoute->isSuccess() && $this->cache->contains($currentRoutePath)) {
            $resp = new \Zend\Diactoros\Response();
            $resp->getBody()->write($this->cache->fetch($currentRoutePath));
            return $resp;
        }

        // If the response is not cached, process the next middleware and get its response, then cache it
        $resp = $delegate->process($request);
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
