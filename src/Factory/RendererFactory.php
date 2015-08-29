<?php
namespace Acelaya\Website\Factory;

use Acelaya\Website\Twig\Extension\TranslatorExtension;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\Twig;

class RendererFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        // Create the twig environment
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem([
            __DIR__ . '/../../templates'
        ]));

        // Add extensions
        $twig->addExtension(new TranslatorExtension($container->get('translator')));

        return new Twig($twig);
    }
}
