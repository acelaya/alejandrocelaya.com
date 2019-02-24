<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Factory;

use Acelaya\Website\Factory\DotNotationConfigAbstractFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceManager;

class DotNotationConfigAbstractFactoryTest extends TestCase
{
    /** @var DotNotationConfigAbstractFactory */
    private $factory;

    public function setUp()
    {
        $this->factory = new DotNotationConfigAbstractFactory();
    }

    /**
     * @param string $serviceName
     * @param bool $canCreate
     *
     * @test
     * @dataProvider provideDotNames
     */
    public function canCreateOnlyServicesWithDot(string $serviceName, bool $canCreate)
    {
        $this->assertEquals($canCreate, $this->factory->canCreate(new ServiceManager(), $serviceName));
    }

    public function provideDotNames(): array
    {
        return [
            ['foo.bar', true],
            ['config.something', true],
            ['config_something', false],
            ['foo', false],
        ];
    }

    /**
     * @test
     */
    public function throwsExceptionWhenFirstPartOfTheServiceIsNotRegistered()
    {
        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            'Defined service "foo" could not be found in container after resolving dotted expression "foo.bar"'
        );

        $this->factory->__invoke(new ServiceManager(), 'foo.bar');
    }

    /**
     * @test
     */
    public function dottedNotetionIsRecursivelyResolvedUntilLastValueIsFoudnAndReturned()
    {
        $expected = 'this is the result';

        $result = $this->factory->__invoke(new ServiceManager(['services' => [
            'foo' => [
                'bar' => ['baz' => $expected],
            ],
        ]]), 'foo.bar.baz');

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function exceptionIsThrownIfAnyStepCannotBeResolved()
    {
        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            'The key "baz" provided in the dotted notation could not be found in the array service'
        );

        $result = $this->factory->__invoke(new ServiceManager(['services' => [
            'foo' => [
                'bar' => ['something' => 123],
            ],
        ]]), 'foo.bar.baz');
    }
}
