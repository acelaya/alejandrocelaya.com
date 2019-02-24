<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Middleware;

use Acelaya\Website\Middleware\LanguageMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouteResult;
use Zend\I18n\Translator\Translator;
use function Zend\Stratigility\middleware;

class LanguageMiddlewareTest extends TestCase
{
    /** @var LanguageMiddleware */
    protected $middleware;
    /** @var Translator */
    protected $translator;

    protected function setUp(): void
    {
        $this->translator = Translator::factory(['locale' => 'en']);
        $this->middleware = new LanguageMiddleware($this->translator);
    }

    public function testLanguage()
    {
        $delegate = $this->prophesize(RequestHandlerInterface::class);
        $delegate->handle(Argument::cetera())->willReturn(new Response());

        $route = new Route('/home', middleware(function () {
            return new Response();
        }), ['GET'], 'home');
        $request = ServerRequestFactory::fromGlobals()->withAttribute(
            RouteResult::class,
            RouteResult::fromRoute($route, ['lang' => 'es'])
        );

        $this->assertEquals('en', $this->translator->getLocale());
        $this->middleware->process($request, $delegate->reveal());
        $this->assertEquals('es', $this->translator->getLocale());
    }

    /**
     * @test
     */
    public function languageIsMatchedFromPathIfRouteIsNotSuccess()
    {

        $delegate = $this->prophesize(RequestHandlerInterface::class);
        $delegate->handle(Argument::cetera())->willReturn(new Response());

        $request = ServerRequestFactory::fromGlobals()->withUri(new Uri('/fr/something'))
                                                      ->withAttribute(
                                                          RouteResult::class,
                                                          RouteResult::fromRouteFailure(null)
                                                      );

        $this->assertEquals('en', $this->translator->getLocale());
        $this->middleware->process($request, $delegate->reveal());
        $this->assertEquals('fr', $this->translator->getLocale());
    }
}
