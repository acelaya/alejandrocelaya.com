<?php
namespace Acelaya\Website\Middleware\Factory;

use Acelaya\Website\Factory\FactoryInterface;
use Acelaya\Website\Middleware\LanguageMiddleware;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\Translator;

class LanguageMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var Translator $translator */
        $translator = $container->get(Translator::class);
        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);

        return new LanguageMiddleware($translator, $router);
    }
}
