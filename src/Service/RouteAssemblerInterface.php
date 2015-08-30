<?php
namespace Acelaya\Website\Service;

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
    public function assembleUrl($name = null, $routeParams = [], $queryParams = [], $inherit = true);
}
