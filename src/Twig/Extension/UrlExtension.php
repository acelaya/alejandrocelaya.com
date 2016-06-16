<?php
namespace Acelaya\Website\Twig\Extension;

use Acelaya\Website\Service\RouteAssemblerInterface;
use Zend\Expressive\Router\RouteResult;

class UrlExtension extends AbstractExtension implements RouteAssemblerInterface
{
    /**
     * @var RouteAssemblerInterface
     */
    protected $routeAssembler;

    public function __construct(RouteAssemblerInterface $routeAssembler)
    {
        $this->routeAssembler = $routeAssembler;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('assemble_url', [$this, 'assembleUrl'])
        ];
    }

    /**
     * @param string|null $name
     * @param array $routeParams
     * @param array $queryParams
     * @param bool|true $inherit
     * @return string
     */
    public function assembleUrl(
        string $name = null,
        $routeParams = [],
        $queryParams = [],
        bool $inherit = false
    ): string {
        return $this->routeAssembler->assembleUrl($name, $routeParams, $queryParams, $inherit);
    }

    /**
     * @return RouteResult
     */
    public function getCurrentRouteResult(): RouteResult
    {
        return $this->routeAssembler->getCurrentRouteResult();
    }
}
