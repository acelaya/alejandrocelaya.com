<?php
declare(strict_types=1);

namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SwiftMailerFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object|\Swift_Mailer
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): \Swift_Mailer
    {
        $mailConfig = $container->get('config')['mail'];
        $smtp = $mailConfig['smtp'];
        $transport = \Swift_SmtpTransport::newInstance($smtp['server'], $smtp['port'], $smtp['ssl'])
                                         ->setUsername($smtp['username'])
                                         ->setPassword($smtp['password']);
        return new \Swift_Mailer($transport);
    }
}
