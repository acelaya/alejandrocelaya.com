<?php
namespace Acelaya\Website\Template\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Zend\I18n\Translator\TranslatorInterface;

class TranslatorExtension implements ExtensionInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('translate', [$this->translator, 'translate']);
        $engine->registerFunction('translate_plural', [$this->translator, 'translatePlural']);
        $engine->registerFunction('locale', [$this->translator, 'getLocale']);
    }
}
