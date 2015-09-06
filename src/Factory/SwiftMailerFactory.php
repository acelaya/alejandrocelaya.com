<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;

class SwiftMailerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        $mailConfig = $container->get('config')['mail'];
        $smtp = $mailConfig['smtp'];
        $transport = \Swift_SmtpTransport::newInstance($smtp['server'], $smtp['port'], $smtp['ssl'])
                                         ->setUsername($smtp['username'])
                                         ->setPassword($smtp['password']);
        return new \Swift_Mailer($transport);
    }
}
