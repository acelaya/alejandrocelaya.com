<?php
declare(strict_types=1);
namespace Acelaya\Website\Service;

use Zend\Expressive\Router\RouteResult;

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
    public function assembleUrl(
        string $name = null,
        $routeParams = [],
        $queryParams = [],
        bool $inherit = false
    ): string;

    /**
     * @return RouteResult
     */
    public function getCurrentRouteResult(): RouteResult;
}
