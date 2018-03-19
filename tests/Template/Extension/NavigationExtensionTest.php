<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Template\Extension;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Template\Extension\NavigationExtension;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouteResult;
use Zend\I18n\Translator\Translator;

class NavigationExtensionTest extends TestCase
{
    /**
     * @var NavigationExtension
     */
    protected $extension;

    public function setUp()
    {
        $routeAssemblerProphezy = $this->prophesize(RouteAssembler::class);
        $routeAssemblerProphezy->assembleUrl('home', true)->willReturn('/foo/bar');
        $routeAssemblerProphezy->assembleUrl(null, ['lang' => 'es'])->willReturn('/es');
        $routeAssemblerProphezy->assembleUrl(null, ['lang' => 'en'])->willReturn('/en');
        $routeAssemblerProphezy->getCurrentRouteResult()->willReturn(RouteResult::fromRoute(new Route(
            'home',
            $this->prophesize(MiddlewareInterface::class)->reveal()
        )));

        $this->extension = new NavigationExtension(
            Translator::factory([]),
            $routeAssemblerProphezy->reveal(),
            [
                'menu' => [
                    [
                        'route' => 'home',
                        'icon' => 'house',
                        'label' => 'Home',
                    ],
                    [
                        'uri' => 'http://foo.com',
                        'target' => true,
                        'label' => 'Blog',
                        'icon' => 'book',
                    ],
                ],
                'lang_menu' => [
                    [
                        'label'    => 'Español',
                        'class'    => 'es',
                        'params'   => [
                            'lang' => 'es',
                        ],
                    ],
                    [
                        'label'    => 'English',
                        'class'    => 'en',
                        'params'   => [
                            'lang' => 'en',
                        ],
                    ],
                ],
                'social_menu' => [
                    [
                        'uri' => 'https://github.com',
                        'icon' => 'github',
                    ],
                    [
                        'uri' => 'https://twitter.com',
                        'icon' => 'twitter',
                    ],
                ],
            ]
        );
    }

    public function testRegister()
    {
        $engine = $this->prophesize(Engine::class);

        $engine->registerFunction('render_menu', Argument::type('callable'))->shouldBeCalledTimes(1);
        $engine->registerFunction('render_langs_menu', Argument::type('callable'))->shouldBeCalledTimes(1);
        $engine->registerFunction('render_social_menu', Argument::type('callable'))->shouldBeCalledTimes(1);

        $this->extension->register($engine->reveal());
    }

    public function testRenderMenu()
    {
        $document = new \DOMDocument();
        $document->loadHTML($this->extension->renderMenu());
        // Discard html and body
        $document = $document->documentElement->firstChild->firstChild;

        $this->assertEquals('ul', $document->tagName);
        $this->assertEquals(2, $document->childNodes->length);
        foreach ($document->childNodes as $child) {
            $this->assertEquals('li', $child->tagName);
        }
        $this->assertEquals('active', $document->firstChild->getAttribute('class'));
        $this->assertEquals('/foo/bar', $document->firstChild->firstChild->getAttribute('href'));
        $this->assertEquals('', $document->lastChild->getAttribute('class'));
        $this->assertEquals('http://foo.com', $document->lastChild->firstChild->getAttribute('href'));
    }

    public function testRenderLanguagesMenu()
    {
        $document = new \DOMDocument();
        $document->loadHTML($this->extension->renderLanguagesMenu());
        // Discard html and body
        $document = $document->documentElement->firstChild->firstChild;

        $this->assertEquals('ul', $document->tagName);
        $this->assertEquals(2, $document->childNodes->length);
        foreach ($document->childNodes as $child) {
            $this->assertEquals('li', $child->tagName);
        }

        $this->assertEquals('/es', $document->firstChild->firstChild->getAttribute('href'));
        $this->assertEquals('/en', $document->lastChild->firstChild->getAttribute('href'));
    }

    /**
     * @test
     */
    public function renderSocialMenuReturnsExpectedInfo()
    {
        $result = $this->extension->renderSocialMenu();

        $this->assertContains(
            '<li><a target="_blank" href="https://github.com"><i class="github"></i></a></li>',
            $result
        );
        $this->assertContains(
            '<li><a target="_blank" href="https://twitter.com"><i class="twitter"></i></a></li>',
            $result
        );
    }
}
