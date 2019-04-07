<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\ErrorHandlerDelegator;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Psr\Log\LoggerInterface;
use ReflectionObject;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\Stratigility\Middleware\ErrorHandler;

class ErrorHandlerDelegatorTest extends TestCase
{
    /** @var ErrorHandlerDelegator */
    private $delegator;

    protected function setUp(): void
    {
        $this->delegator = new ErrorHandlerDelegator();
    }

    /**
     * @test
     */
    public function serviceIsProperlyDecorated()
    {
        $errorHandler = $this->delegator->__invoke(new ServiceManager(), '', function () {
            return new ErrorHandler(function () {
                return new Response();
            });
        });

        $ref = new ReflectionObject($errorHandler);
        $prop = $ref->getProperty('listeners');
        $prop->setAccessible(true);

        $this->assertInstanceOf(ErrorHandler::class, $errorHandler);
        $this->assertCount(1, $prop->getValue($errorHandler));
    }

    /**
     * @test
     */
    public function logErrorFetchesLoggerAndLogsError()
    {
        $logger = $this->prophesize(LoggerInterface::class);

        $ref = new ReflectionObject($this->delegator);
        $prop = $ref->getProperty('container');
        $prop->setAccessible(true);
        $prop->setValue($this->delegator, new ServiceManager(['services' => [
            LoggerInterface::class => $logger->reveal(),
        ]]));

        /** @var MethodProphecy $error */
        $error = $logger->error(Argument::cetera())->will(function () {
        });

        $this->delegator->logError(new Exception('Error'), ServerRequestFactory::fromGlobals());

        $error->shouldHaveBeenCalled();
    }
}
