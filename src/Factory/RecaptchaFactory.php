<?php
namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use ReCaptcha\ReCaptcha;

class RecaptchaFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        $recaptchaConfig = $container->get('config')['recaptcha'];
        return new ReCaptcha($recaptchaConfig['private_key']);
    }
}
