<?php
namespace Acelaya\Website\Action;

use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

abstract class AbstractAction implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    protected $renderer;
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * AbstractAction constructor.
     * @param TemplateRendererInterface $renderer
     *
     * @Inject({"renderer"})
     */
    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
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
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null): Response
    {
        return $this->dispatch($request, $response, $out);
    }

    /**
     * Returns the content to render
     *
     * @param Request $request
     * @param Response $response
     * @param null|callable $next
     * @return null|Response
     */
    abstract public function dispatch(Request $request, Response $response, callable $next = null): Response;
}
