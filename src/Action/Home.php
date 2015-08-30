<?php
namespace Acelaya\Website\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Home extends AbstractAction
{
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $renderedContent = $this->renderer->render('home.html.twig');

        // Cache content for further requests
        $this->cache->save($request->getUri()->getPath(), $renderedContent);

        // Write the content in the response
        $response->getBody()->write($renderedContent);
        return $response;
    }
}
