<?php
declare(strict_types=1);

namespace Acelaya\Website\Action;

use Doctrine\Common\Cache\Cache;
use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

abstract class AbstractAction implements RequestHandlerInterface, RequestMethodInterface, StatusCodeInterface
{
    /** @var TemplateRendererInterface */
    protected $renderer;
    /** @var Cache */
    protected $cache;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        return $this->dispatch($request);
    }

    /**
     * Returns the content to render
     *
     * @param Request $request
     * @return Response
     */
    abstract public function dispatch(Request $request): Response;
}
