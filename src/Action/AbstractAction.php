<?php
namespace Acelaya\Website\Action;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Template\TemplateInterface;

abstract class AbstractAction
{
    /**
     * @var TemplateInterface
     */
    protected $renderer;

    public function __construct(TemplateInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        return $this->dispatch($request, $response, $next);
    }

    abstract public function dispatch(RequestInterface $request, ResponseInterface $response, callable $next);
}
