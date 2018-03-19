<?php
declare(strict_types=1);

namespace Acelaya\Website\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorInterface;

class LanguageMiddleware implements MiddlewareInterface
{
    /**
     * @var TranslatorInterface|Translator
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
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param Request $request
     * @param RequestHandlerInterface $handler
     *
     * @return Response
     */
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $matchedRoute = $this->router->match($request);
        $lang = $matchedRoute->isFailure()
            ? $this->matchLanguageFromPath($request)
            : $this->matchLanguageFromParams($matchedRoute);

        $this->translator->setLocale($lang);
        return $handler->handle($request);
    }

    private function matchLanguageFromPath(Request $request): string
    {
        $path = $request->getUri()->getPath();
        $parts = array_filter(explode('/', $path), function (string $value) {
            return ! empty($value);
        });
        return strtolower(array_shift($parts) ?? '');
    }

    private function matchLanguageFromParams(RouteResult $routeResult): string
    {
        $params = $routeResult->getMatchedParams();
        return $params['lang'] ?? 'en';
    }
}
