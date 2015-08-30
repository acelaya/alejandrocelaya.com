<?php
namespace AcelayaTest\Website\Twig\Extension;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Twig\Extension\UrlExtension;
use PHPUnit_Framework_TestCase as TestCase;

class UrlExtensionTest extends TestCase
{
    /**
     * @var UrlExtension
     */
    protected $extension;

    public function setUp()
    {
        $routeAssembler = $this->prophesize(RouteAssembler::class);
        $routeAssembler->assembleUrl('home', [], [], false)->willReturn('/foo/bar');
        $this->extension = new UrlExtension($routeAssembler->reveal());
    }

    public function testGetFunctions()
    {
        /** @var \Twig_SimpleFunction[] $funcs */
        $funcs = $this->extension->getFunctions();
        $this->assertCount(1, $funcs);
        $this->assertEquals('assemble_url', $funcs[0]->getName());
    }

    public function testAssembleUrl()
    {
        $this->assertEquals('/foo/bar', $this->extension->assembleUrl('home'));
    }
}
