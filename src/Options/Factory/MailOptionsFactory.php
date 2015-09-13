<?php
namespace Acelaya\Website\Options\Factory;

use Acelaya\Website\Factory\FactoryInterface;
use Acelaya\Website\Options\MailOptions;
use Interop\Container\ContainerInterface;

class MailOptionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new MailOptions(isset($config['mail']) ? $config['mail'] : []);
    }
}
