<?php
declare(strict_types=1);

namespace Acelaya\Website\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;
use Zend\Stratigility\Middleware\ErrorHandler;

use const PHP_EOL;

use function sprintf;

class ErrorHandlerDelegator implements DelegatorFactoryInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * A factory that creates delegates of a given service
     *
     * @param  ContainerInterface $container
     * @param  string $name
     * @param  callable $callback
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $this->container = $container;

        /** @var ErrorHandler $errorHandler */
        $errorHandler = $callback();
        $errorHandler->attachListener([$this, 'logError']);

        return $errorHandler;
    }

    public function logError(Throwable $e, ServerRequestInterface $request): void
    {
        /** @var LoggerInterface $logger */
        $logger = $this->container->get(LoggerInterface::class);
        $logger->error(sprintf(
            'An error occurred while processing request for URI "%s"',
            (string) $request->getUri()
        ) . PHP_EOL . $e);
    }
}
