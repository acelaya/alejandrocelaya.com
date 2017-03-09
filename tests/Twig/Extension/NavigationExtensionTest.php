<?php
namespace AcelayaTest\Website\Twig\Extension;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Twig\Extension\NavigationExtension;
use PHPUnit\Framework\TestCase;
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
        $routeAssemblerProphezy->getCurrentRouteResult()->willReturn(RouteResult::fromRouteMatch(
            'home',
            'HelloWorld',
            []
        ));

        $this->extension = new NavigationExtension(
            Translator::factory([]),
            $routeAssemblerProphezy->reveal(),
            [
                'menu' => [
                    [
                        'route' => 'home',
                        'icon' => 'house',
                        'label' => 'Home'
                    ],
                    [
                        'uri' => 'http://foo.com',
                        'target' => true,
                        'label' => 'Blog',
                        'icon' => 'book'
                    ]
                ],
                'lang_menu' => [
                    [
                        'label'    => 'Español',
                        'class'    => 'es',
                        'params'   => [
                            'lang' => 'es'
                        ]
                    ],
                    [
                        'label'    => 'English',
                        'class'    => 'en',
                        'params'   => [
                            'lang' => 'en'
                        ]
                    ],
                ]
            ]
        );
    }

    public function testGetFunctions()
    {
        $funcs = $this->extension->getFunctions();
        $this->assertCount(3, $funcs);
        $this->assertInstanceOf(\Twig_SimpleFunction::class, $funcs[0]);
        $this->assertInstanceOf(\Twig_SimpleFunction::class, $funcs[1]);
        $this->assertInstanceOf(\Twig_SimpleFunction::class, $funcs[2]);
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
}
