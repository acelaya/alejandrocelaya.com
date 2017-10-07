<?php
declare(strict_types=1);
namespace Acelaya\Website\Middleware;

use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\TranslatorInterface;

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

    /**
     * LanguageMiddleware constructor.
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     *
     * @Inject({"translator", RouterInterface::class})
     */
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
     * @param DelegateInterface $delegate
     *
     * @return Response
     */
    public function process(Request $request, DelegateInterface $delegate)
    {
        $matchedRoute = $this->router->match($request);
        $params = $matchedRoute->getMatchedParams();

        // Determine the language to use based on the lang parameter
        $lang = $params['lang'] ?? 'en';
        $this->translator->setLocale($lang);
        return $delegate->process($request);
    }
}
