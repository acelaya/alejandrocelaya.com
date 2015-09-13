<?php
namespace Acelaya\Website\Service\Factory;

use Acelaya\Website\Factory\FactoryInterface;
use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service\ContactService;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateInterface;

class ContactServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return new ContactService(
            $container->get(\Swift_Mailer::class),
            $container->get(TemplateInterface::class),
            $container->get(MailOptions::class)
        );
    }
}
