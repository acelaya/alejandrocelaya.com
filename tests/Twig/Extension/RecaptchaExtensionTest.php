<?php
namespace AcelayaTest\Website\Twig\Extension;

use Acelaya\Website\Twig\Extension\RecaptchaExtension;
use PHPUnit_Framework_TestCase as TestCase;

class RecaptchaExtensionTest extends TestCase
{
    /**
     * @var RecaptchaExtension
     */
    protected $extension;

    public function setUp()
    {
        $this->extension = new RecaptchaExtension([
            'public_key' => 'my_key'
        ]);
    }

    public function testGetFunctions()
    {
        $funcs = $this->extension->getFunctions();
        $this->assertCount(2, $funcs);
        $this->assertInstanceOf(\Twig_SimpleFunction::class, $funcs[0]);
        $this->assertInstanceOf(\Twig_SimpleFunction::class, $funcs[1]);
    }

    public function testGetRecaptchaPublicKey()
    {
        $this->assertEquals('my_key', $this->extension->getRecapcthaPublicKey());
    }

    public function testRenderInput()
    {
        $document = new \DOMDocument();
        $document->loadHTML($this->extension->renderInput());
        // Discard HTML
        $head = $document->documentElement->firstChild;
        $body = $document->documentElement->lastChild;

        $this->assertEquals('script', $head->firstChild->tagName);
        $this->assertContains('hl=en', $head->firstChild->getAttribute('src'));

        $this->assertEquals('div', $body->firstChild->tagName);
        $this->assertEquals('my_key', $body->firstChild->getAttribute('data-sitekey'));
    }
}
