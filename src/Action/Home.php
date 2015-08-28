<?php
namespace Acelaya\Website\Action;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Home extends AbstractAction
{
    public function dispatch(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response->getBody()->write($this->renderer->render('home.html.twig'));
        return $response;
    }
}
