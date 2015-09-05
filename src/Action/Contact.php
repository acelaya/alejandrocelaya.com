<?php
namespace Acelaya\Website\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Contact extends Template
{
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
        if ($request->getMethod() === 'GET') {
            return $this->createTemplateResponse($request);
        } else {
            $params = $request->getParsedBody();
            return new RedirectResponse($params['currentRoute']);
        }
    }
}
