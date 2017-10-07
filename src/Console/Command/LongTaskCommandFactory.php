<?php
declare(strict_types=1);

namespace Acelaya\Website\Console\Command;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class LongTaskCommandFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return LongTasksCommand|object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LongTasksCommand
    {
        $config = $container->get('config')['long_tasks'];
        $tasks = $config['tasks'] ?? [];

        foreach ($tasks as $key => $task) {
            if (! $container->has($task)) {
                unset($tasks[$key]);
                continue;
            }

            $tasks[$key] = $container->get($task);
        }

        return new LongTasksCommand($tasks);
    }
}
