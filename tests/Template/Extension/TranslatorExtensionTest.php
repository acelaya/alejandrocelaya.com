<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Template\Extension;

use Acelaya\Website\Template\Extension\TranslatorExtension;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\I18n\Translator\Translator;

class TranslatorExtensionTest extends TestCase
{
    /** @var TranslatorExtension */
    protected $extension;
    /** @var Translator */
    protected $translator;

    public function setUp()
    {
        $this->translator = Translator::factory(['locale' => 'en']);
        $this->extension = new TranslatorExtension($this->translator);
    }

    public function testRegister()
    {
        $engine = $this->prophesize(Engine::class);

        $engine->registerFunction('translate', Argument::type('callable'))->shouldBeCalledTimes(1);
        $engine->registerFunction('translate_plural', Argument::type('callable'))->shouldBeCalledTimes(1);
        $engine->registerFunction('locale', Argument::type('callable'))->shouldBeCalledTimes(1);

        $this->extension->register($engine->reveal());
    }
}
