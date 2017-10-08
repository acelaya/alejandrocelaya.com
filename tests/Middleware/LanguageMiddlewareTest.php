<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Middleware;

use Acelaya\Website\Middleware\LanguageMiddleware;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;
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
     * @var Translator
     */
    protected $translator;
    /**
     * @var ObjectProphecy
     */
    protected $router;

    public function setUp()
    {
        $this->router = $this->prophesize(RouterInterface::class);
        $this->translator = Translator::factory(['locale' => 'en']);

        $this->middleware = new LanguageMiddleware($this->translator, $this->router->reveal());
    }

    public function testLanguage()
    {
        $request = ServerRequestFactory::fromGlobals();

        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate->process(Argument::cetera())->willReturn(new Response());

        $route = new Route('/home', function () {
            return new Response();
        }, ['GET'], 'home');
        $this->router->match($request)->willReturn(RouteResult::fromRoute($route, ['lang' => 'es']));

        $this->assertEquals('en', $this->translator->getLocale());
        $this->middleware->process($request, $delegate->reveal());
        $this->assertEquals('es', $this->translator->getLocale());
    }

    /**
     * @test
     */
    public function languageIsMatchedFromPathIfRouteIsNotSuccess()
    {
        $request = ServerRequestFactory::fromGlobals()->withUri(new Uri('/fr/something'));

        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate->process(Argument::cetera())->willReturn(new Response());

        /** @var MethodProphecy $match */
        $match = $this->router->match($request)->willReturn(RouteResult::fromRouteFailure());

        $this->assertEquals('en', $this->translator->getLocale());
        $this->middleware->process($request, $delegate->reveal());
        $this->assertEquals('fr', $this->translator->getLocale());

        $match->shouldHaveBeenCalled();
    }
}
