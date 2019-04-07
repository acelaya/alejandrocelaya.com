<?php
declare(strict_types=1);

namespace Acelaya\Website\Template\Extension;

use Acelaya\Website\Service\RouteAssemblerInterface;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class UrlExtension implements ExtensionInterface
{
    /** @var RouteAssemblerInterface */
    protected $routeAssembler;

    public function __construct(RouteAssemblerInterface $routeAssembler)
    {
        $this->routeAssembler = $routeAssembler;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('assemble_url', [$this->routeAssembler, 'assembleUrl']);
        $engine->registerFunction('current_route', [$this, 'getCurrentRouteName']);
    }

    /**
     * @return string
     */
    public function getCurrentRouteName(): string
    {
        $routeResult = $this->routeAssembler->getCurrentRouteResult();
        return $routeResult->isSuccess() ? $routeResult->getMatchedRouteName() : '';
    }
}
