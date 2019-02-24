<?php
declare(strict_types=1);

namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

use const PHP_EOL;

class LoggerFactory implements FactoryInterface
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
        $loggerConfig = $container->get('config')['logger'];
        $isDebug = $container->get('config')['debug'] ?? false;

        $formatter = new LineFormatter('[%datetime%] %channel%.%level_name% - %message% %context%' . PHP_EOL);
        $formatter->includeStacktraces();

        $handler = new StreamHandler($loggerConfig['file'], $isDebug ? Logger::DEBUG : Logger::INFO);
        $handler->setFormatter($formatter);

        return new Logger('alejandrocelaya.com', [$handler]);
    }
}
