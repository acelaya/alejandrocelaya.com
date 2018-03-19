<?php
declare(strict_types=1);

namespace Acelaya\Website\Service;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;

class RouteAssembler implements RouteAssemblerInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var callable
     */
    protected $requestFactory;

    public function __construct(RouterInterface $router, callable $requestFactory)
    {
        $this->router = $router;
        $this->requestFactory = $requestFactory;
    }

    /**
     * Assembles a route with given name using given information
     *
     * @param string|null $name
     * @param array|bool $routeParams
     * @param array|bool $queryParams
     * @param bool $inherit Tells if route and query params should be inherited from current route
     * @return string
     */
    public function assembleUrl(
        string $name = null,
        $routeParams = [],
        $queryParams = [],
        bool $inherit = false
    ): string {
        $routeResult = $this->getCurrentRouteResult();
        $factory = $this->requestFactory;
        /** @var ServerRequestInterface $request */
        $request = $factory();

        if ($name === null) {
            $name = $routeResult->isSuccess() ? $routeResult->getMatchedRouteName() : 'home';
        }

        if (is_bool($routeParams)) {
            $inherit = $routeParams;
            $routeParams = [];
        }

        if (is_bool($queryParams)) {
            $inherit = $queryParams;
            $queryParams = [];
        }

        if ($inherit) {
            $routeParams = array_merge($routeResult->getMatchedParams(), $routeParams);
            $queryParams = array_merge($request->getQueryParams(), $queryParams);
        }

        $queryString = empty($queryParams) ? '' : sprintf('?%s', http_build_query($queryParams));
        return $this->router->generateUri($name, $routeParams) . $queryString;
    }

    /**
     * @return RouteResult
     */
    public function getCurrentRouteResult(): RouteResult
    {
        $factory = $this->requestFactory;
        /** @var ServerRequestInterface $request */
        $request = $factory();
        return $this->router->match($request);
    }
}
