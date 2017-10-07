<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Middleware;

use Acelaya\Website\Middleware\LanguageMiddleware;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Router\Route;
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

        $route = new Route('/home', function () {
            return new Response();
        }, ['GET'], 'home');
        $router->match($this->request)->willReturn(RouteResult::fromRoute($route, ['lang' => 'es']));
        $this->translator = Translator::factory(['locale' => 'en']);
        $this->middleware = new LanguageMiddleware($this->translator, $router->reveal());
    }

    public function testLanguage()
    {
        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate->process(Argument::cetera())->willReturn(new Response());

        $this->assertEquals('en', $this->translator->getLocale());
        $this->middleware->process($this->request, $delegate->reveal());
        $this->assertEquals('es', $this->translator->getLocale());
    }
}
