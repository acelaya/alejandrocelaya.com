<?php
namespace Acelaya\Website\Twig\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Zend\I18n\Translator\TranslatorInterface;

class TranslatorExtension extends AbstractExtension implements ExtensionInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('translate', [$this->translator, 'translate']),
            new \Twig_SimpleFunction('translate_plural', [$this->translator, 'translatePlural']),
            new \Twig_SimpleFunction('locale', [$this->translator, 'getLocale']),
        ];
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('translate', [$this->translator, 'translate']);
        $engine->registerFunction('translate_plural', [$this->translator, 'translatePlural']);
        $engine->registerFunction('locale', [$this->translator, 'getLocale']);
    }
}
