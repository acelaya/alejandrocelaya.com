<?php
namespace Acelaya\Website\Factory;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Twig\Extension\NavigationExtension;
use Acelaya\Website\Twig\Extension\RecaptchaExtension;
use Acelaya\Website\Twig\Extension\TranslatorExtension;
use Acelaya\Website\Twig\Extension\UrlExtension;
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
        $twig->addExtension(new UrlExtension($container->get(RouteAssembler::class)));
        $twig->addExtension(new NavigationExtension(
            $container->get('translator'),
            $container->get(RouteAssembler::class),
            $container->get('config')['navigation']
        ));
        $twig->addExtension(new RecaptchaExtension($container->get('config')['recaptcha']));

        return new Twig($twig);
    }
}
