<?php
declare(strict_types=1);
namespace Acelaya\Website\Action;

use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Doctrine\Common\Cache\Cache;
use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;

abstract class AbstractAction implements MiddlewareInterface, RequestMethodInterface, StatusCodeInterface
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
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param Request $request
     * @param DelegateInterface $delegate
     *
     * @return Response
     */
    public function process(Request $request, DelegateInterface $delegate)
    {
        return $this->dispatch($request, $delegate);
    }

    /**
     * Returns the content to render
     *
     * @param Request $request
     * @param DelegateInterface $delegate
     * @return Response
     */
    abstract public function dispatch(Request $request, DelegateInterface $delegate): Response;
}
