<?php
namespace Acelaya\Website\Twig\Extension;

use Acelaya\Website\Service\RouteAssemblerInterface;

class UrlExtension extends AbstractExtension
{
    /**
     * @var RouteAssemblerInterface
     */
    protected $routeAssembler;

    public function __construct(RouteAssemblerInterface $routeAssembler)
    {
        $this->routeAssembler = $routeAssembler;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('assemble_url', [$this, 'assembleUrl'])
        ];
    }

    /**
     * @param null $name
     * @param array $routeParams
     * @param array $queryParams
     * @param bool|true $inherit
     * @return string
     */
    public function assembleUrl($name = null, $routeParams = [], $queryParams = [], $inherit = true)
    {
        return $this->routeAssembler->assembleUrl($name, $routeParams, $queryParams, $inherit);
    }
}
