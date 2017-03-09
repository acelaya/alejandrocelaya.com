<?php
namespace AcelayaTest\Website\Middleware;

use Acelaya\Website\Middleware\LanguageMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\Translator;

class LanguageMiddlewareTest extends TestCase
{
    /**
     * @var LanguageMiddleware
     */
    protected $middleware;
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var Translator
     */
    protected $translator;

    public function setUp()
    {
        $this->request = new ServerRequest();
        $router = $this->prophesize(RouterInterface::class);
        $router->match($this->request)->willReturn(RouteResult::fromRouteMatch('home', function ($req, $resp) {
            return $resp;
        }, ['lang' => 'es']));
        $this->translator = Translator::factory(['locale' => 'en']);
        $this->middleware = new LanguageMiddleware($this->translator, $router->reveal());
    }

    public function testLanguage()
    {
        $this->assertEquals('en', $this->translator->getLocale());
        $this->middleware->__invoke($this->request, new Response(), function ($req, $resp) {
            return $resp;
        });
        $this->assertEquals('es', $this->translator->getLocale());
    }
}
