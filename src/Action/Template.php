<?php
namespace Acelaya\Website\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Template extends AbstractAction
{
    const NOT_FOUND_TEMPLATE = 'errors/404.html.twig';

    /**
     * Returns the content to render
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param null|callable $next
     * @return null|ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return $this->createTemplateResponse($request);
    }

    /**
     * Creates an HtmlResponse rendering current route's template
     *
     * @param ServerRequestInterface $request
     * @param array $viewParams
     * @return HtmlResponse
     */
    protected function createTemplateResponse(ServerRequestInterface $request, array $viewParams = [])
    {
        $template = $request->getAttribute('template', self::NOT_FOUND_TEMPLATE);
        $status = $template === self::NOT_FOUND_TEMPLATE ? 404 : 200;
        return new HtmlResponse($this->renderer->render($template, $viewParams), $status);
    }
}
