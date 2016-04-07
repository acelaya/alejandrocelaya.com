<?php
namespace Acelaya\Website\Factory;

use Acelaya\Website\Service\RouteAssembler;
use Acelaya\Website\Twig\Extension\NavigationExtension;
use Acelaya\Website\Twig\Extension\RecaptchaExtension;
use Acelaya\Website\Twig\Extension\TranslatorExtension;
use Acelaya\Website\Twig\Extension\UrlExtension;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class RendererFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Create the twig environment
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem([
            __DIR__ . '/../../templates'
        ]), [
            'cache' => getenv('APP_ENV') === 'pro' ? __DIR__ . '/../../data/cache' : false,
        ]);

        // Add extensions
        $twig->addExtension(new TranslatorExtension($container->get('translator')));
        $twig->addExtension(new UrlExtension($container->get(RouteAssembler::class)));
        $twig->addExtension(new NavigationExtension(
            $container->get('translator'),
            $container->get(RouteAssembler::class),
            $container->get('config')['navigation']
        ));
        $twig->addExtension(new RecaptchaExtension($container->get('config')['recaptcha']));

        return new TwigRenderer($twig);
    }
}
