<?php
namespace Acelaya\Website\Action;

use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template\TemplateInterface;
use Zend\Stratigility\MiddlewareInterface;

abstract class AbstractAction implements MiddlewareInterface
{
    /**
     * @var TemplateInterface
     */
    protected $renderer;
    /**
     * @var Cache
     */
    protected $cache;

    public function __construct(TemplateInterface $renderer, Cache $cache)
    {
        $this->renderer = $renderer;
        $this->cache = $cache;
    }

    /**
     * Process an incoming request and/or response.
     *
     * Accepts a server-side request and a response instance, and does
     * something with them.
     *
     * If the response is not complete and/or further processing would not
     * interfere with the work done in the middleware, or if the middleware
     * wants to delegate to another process, it can use the `$out` callable
     * if present.
     *
     * If the middleware does not return a value, execution of the current
     * request is considered complete, and the response instance provided will
     * be considered the response to return.
     *
     * Alternately, the middleware may return a response instance.
     *
     * Often, middleware will `return $out();`, with the assumption that a
     * later middleware will return a response.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param null|callable $out
     * @return null|ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        return $this->dispatch($request, $response, $out);
    }

    /**
     * Returns the content to render
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param null|callable $next
     * @return null|ResponseInterface
     */
    abstract public function dispatch(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    );
}
