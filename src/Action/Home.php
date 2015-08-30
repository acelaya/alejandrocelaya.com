<?php
namespace Acelaya\Website\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Home extends AbstractAction
{
    /**
     * Returns the content to render
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return string
     */
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        return $this->renderer->render('home.html.twig');
    }
}
