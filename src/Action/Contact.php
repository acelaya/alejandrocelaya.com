<?php
namespace Acelaya\Website\Action;

use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Service\ContactServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template\TemplateInterface;
use Zend\Session\Container;

class Contact extends Template
{
    const PRG_DATA = 'prg_contact_data';

    /**
     * @var ContactServiceInterface
     */
    protected $contactService;
    /**
     * @var ContactFilter
     */
    protected $contactFilter;
    /**
     * @var \ArrayAccess
     */
    protected $session;

    public function __construct(
        TemplateInterface $renderer,
        ContactServiceInterface $contactService,
        ContactFilter $contactFilter,
        \ArrayAccess $session = null
    ) {
        parent::__construct($renderer);
        $this->contactService = $contactService;
        $this->contactFilter = $contactFilter;
        $this->container = $session ?: new Container(__CLASS__);
    }

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
        // On GET requests that are not comming from PRG, just return the template
        if ($request->getMethod() === 'GET' && ! $this->container->offsetExists(self::PRG_DATA)) {
            return $this->createTemplateResponse($request);
        }

        // On POST requests, get request data, store it in the session, and redirect to sel
        if ($request->getMethod() === 'POST') {
            $params = $request->getParsedBody();
            $this->container->offsetSet(self::PRG_DATA, $params);
            return new RedirectResponse($request->getUri());
        }

        // On a GET request that contains data, process the data and clear it from session
        $params = $this->container->offsetGet(self::PRG_DATA);
        $this->container->offsetUnset(self::PRG_DATA);
        $filter = $this->contactFilter;
        $filter->setData($params);
        if (! $filter->isValid()) {
            return $this->createTemplateResponse($request, [
                'errors' => $filter->getMessages(),
                'currentData' => $params
            ]);
        }

        // If the form is valid, send the email
        $result = $this->contactService->send($filter->getValues());
        return $this->createTemplateResponse($request, $result ? ['success' => true] : ['errors' => []]);
    }
}
