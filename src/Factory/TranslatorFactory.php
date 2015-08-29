<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;

class TranslatorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        $translatorConfig = $container->get('config')['translator'];
        return Translator::factory($translatorConfig);
    }
}
