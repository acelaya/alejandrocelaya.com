<?php
namespace Acelaya\Website\Service;

use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;

interface RouteAssemblerInterface
{
    /**
     * Assembles a route with given name using given information
     *
     * @param string|null $name
     * @param array|bool $routeParams
     * @param array|bool $queryParams
     * @param bool $inherit Tells if route and query params should be inherited from current route
     * @return string
     */
    public function assembleUrl($name = null, $routeParams = [], $queryParams = [], $inherit = false);

    /**
     * @return RouteResult
     */
    public function getCurrentRouteResult();
}
