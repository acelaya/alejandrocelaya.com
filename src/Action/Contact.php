<?php
declare(strict_types=1);

namespace Acelaya\Website\Action;

use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Service\ContactServiceInterface;
use ArrayAccess;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Session\Container;

class Contact extends Template
{
    const PRG_DATA = 'prg_contact_data';

    /** @var ContactServiceInterface */
    protected $contactService;
    /** @var ContactFilter */
    protected $contactFilter;
    /** @var \ArrayAccess */
    protected $session;

    public function __construct(
        TemplateRendererInterface $renderer,
        ContactServiceInterface $contactService,
        ContactFilter $contactFilter,
        ArrayAccess $session = null
    ) {
        parent::__construct($renderer);
        $this->contactService = $contactService;
        $this->contactFilter = $contactFilter;
        $this->session= $session ?: new Container(__CLASS__);
    }

    /**
     * Returns the content to render
     *
     * @param Request $request
     * @return Response
     */
    public function dispatch(Request $request): Response
    {
        // On GET requests that are not comming from PRG, just return the template
        if ($request->getMethod() === self::METHOD_GET && ! $this->session->offsetExists(self::PRG_DATA)) {
            return $this->createTemplateResponse($request);
        }

        // On POST requests, get request data, store it in the session, and redirect to sel
        if ($request->getMethod() === self::METHOD_POST) {
            $params = $request->getParsedBody();
            $this->session->offsetSet(self::PRG_DATA, $params);
            return new RedirectResponse($request->getUri());
        }

        // On a GET request that contains data, process the data and clear it from session
        $params = $this->session->offsetGet(self::PRG_DATA);
        $this->session->offsetUnset(self::PRG_DATA);
        $filter = $this->contactFilter;
        $filter->setData($params);
        if (! $filter->isValid()) {
            return $this->createTemplateResponse($request, [
                'errors' => $filter->getMessages(),
                'currentData' => $params,
            ]);
        }

        // If the form is valid, send the email
        $result = $this->contactService->send($filter->getValues());
        return $this->createTemplateResponse($request, $result ? ['success' => true] : ['errors' => []]);
    }
}
