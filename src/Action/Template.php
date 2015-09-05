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
     * @param callable $next
     * @return string
     */
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $template = $request->getAttribute('template', self::NOT_FOUND_TEMPLATE);
        $status = $template === self::NOT_FOUND_TEMPLATE ? 404 : 200;
        return new HtmlResponse($this->renderer->render($template), $status);
    }
}
