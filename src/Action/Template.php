<?php
declare(strict_types=1);

namespace Acelaya\Website\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;

class Template extends AbstractAction
{
    const NOT_FOUND_TEMPLATE = 'Acelaya::errors/404';

    /**
     * Returns the content to render
     *
     * @param Request $request
     * @return null|Response
     */
    public function dispatch(Request $request): Response
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
        $status = $template === self::NOT_FOUND_TEMPLATE ? self::STATUS_NOT_FOUND : self::STATUS_OK;
        return new HtmlResponse($this->renderer->render($template, $viewParams), $status);
    }
}
