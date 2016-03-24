<?php
namespace Acelaya\Website\Action\Factory;

use Acelaya\Website\Action\Contact;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Service\ContactService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use ReCaptcha\ReCaptcha;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ContactFactory implements FactoryInterface
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
        return new Contact(
            $container->get(TemplateRendererInterface::class),
            $container->get(ContactService::class),
            new ContactFilter($container->get(ReCaptcha::class))
        );
    }
}
