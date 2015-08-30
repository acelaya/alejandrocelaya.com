<?php
namespace Acelaya\Website\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Stratigility\MiddlewareInterface;

class LanguageMiddleware implements MiddlewareInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * Process an incoming request and/or response.
     *
     * Accepts a server-side request and a response instance, and does
     * something with them.
     *
     * If the response is not complete and/or further processing would not
     * interfere with the work done in the middleware, or if the middleware
     * wants to delegate to another process, it can use the `$out` callable
     * if present.
     *
     * If the middleware does not return a value, execution of the current
     * request is considered complete, and the response instance provided will
     * be considered the response to return.
     *
     * Alternately, the middleware may return a response instance.
     *
     * Often, middleware will `return $out();`, with the assumption that a
     * later middleware will return a response.
     *
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $matchedRoute = $this->router->match($request);
        $params = $matchedRoute->getMatchedParams();

        // Determine the language to use based on the lang parameter
        $lang = isset($params['lang']) ? $params['lang'] : 'en';
        $this->translator->setLocale($lang);
        return $out($request, $response);
    }
}
