<?php
namespace Acelaya\Website\Middleware\Factory;

use Acelaya\Website\Middleware\LanguageMiddleware;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class LanguageMiddlewareFactory implements FactoryInterface
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
        /** @var Translator $translator */
        $translator = $container->get(Translator::class);
        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);

        return new LanguageMiddleware($translator, $router);
    }
}
