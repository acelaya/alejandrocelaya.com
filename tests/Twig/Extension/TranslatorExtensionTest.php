<?php
namespace AcelayaTest\Website\Twig\Extension;

use Acelaya\Website\Twig\Extension\TranslatorExtension;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\I18n\Translator\Translator;

class TranslatorExtensionTest extends TestCase
{
    /**
     * @var TranslatorExtension
     */
    protected $extension;
    /**
     * @var Translator
     */
    protected $translator;

    public function setUp()
    {
        $this->translator = Translator::factory(['locale' => 'en']);
        $this->extension = new TranslatorExtension($this->translator);
    }

    public function testGetFunctions()
    {
        /** @var \Twig_SimpleFunction[] $funcs */
        $funcs = $this->extension->getFunctions();
        $this->assertCount(3, $funcs);
        $this->assertEquals('translate', $funcs[0]->getName());
        $this->assertEquals('translate_plural', $funcs[1]->getName());
        $this->assertEquals('locale', $funcs[2]->getName());
    }

    public function testTranslate()
    {
        $message = 'foo bar';
        $this->assertEquals($this->translator->translate($message), $this->extension->translate($message));
    }

    public function testTranslatePlural()
    {
        $singular = 'foo bar';
        $plural = 'bar foo';
        $number = 1;
        $this->assertEquals(
            $this->translator->translatePlural($singular, $plural, $number),
            $this->extension->translatePlural($singular, $plural, $number)
        );
    }

    public function testGetLocale()
    {
        $this->assertEquals('en', $this->extension->getLocale());
        $this->translator->setLocale('es');
        $this->assertEquals('es', $this->extension->getLocale());
    }
}
