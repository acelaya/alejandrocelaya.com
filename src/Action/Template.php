<?php
namespace Acelaya\Website\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;

class Template extends AbstractAction
{
    const NOT_FOUND_TEMPLATE = 'errors/404.html.twig';

    /**
     * Returns the content to render
     *
     * @param Request $request
     * @param Response $response
     * @param null|callable $next
     * @return null|Response
     */
    public function dispatch(Request $request, Response $response, callable $next = null): Response
    {
        return $this->createTemplateResponse($request);
    }

    /**
     * Creates an HtmlResponse rendering current route's template
     *
     * @param Request $request
     * @param array $viewParams
     * @return HtmlResponse
     */
    protected function createTemplateResponse(Request $request, array $viewParams = []): HtmlResponse
    {
        $template = $request->getAttribute('template', self::NOT_FOUND_TEMPLATE);
        $status = $template === self::NOT_FOUND_TEMPLATE ? 404 : 200;
        return new HtmlResponse($this->renderer->render($template, $viewParams), $status);
    }
}
