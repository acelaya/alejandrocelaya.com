<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\Twig;

class RendererFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return new Twig(new \Twig_Environment(new \Twig_Loader_Filesystem([
            __DIR__ . '/../../templates'
        ])));
    }
}
