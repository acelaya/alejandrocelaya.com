<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Template\Extension;

use Acelaya\Website\Template\Extension\RecaptchaExtension;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class RecaptchaExtensionTest extends TestCase
{
    /**
     * @var RecaptchaExtension
     */
    protected $extension;

    public function setUp()
    {
        $this->extension = new RecaptchaExtension([
            'public_key' => 'my_key',
        ]);
    }

    public function testRegister()
    {
        $engine = $this->prophesize(Engine::class);

        $engine->registerFunction('recaptcha_public', Argument::type('callable'))->shouldBeCalledTimes(1);
        $engine->registerFunction('recaptcha_input', Argument::type('callable'))->shouldBeCalledTimes(1);

        $this->extension->register($engine->reveal());
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
