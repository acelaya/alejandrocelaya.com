<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Action;

use Acelaya\Website\Action\Redirect;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequestFactory;

class RedirectTest extends TestCase
{
    /**
     * @var Redirect
     */
    private $action;

    public function setUp()
    {
        $this->action = new Redirect();
    }

    /**
     * @test
     */
    public function redirectIsReturned()
    {
        $resp = $this->action->process(
            ServerRequestFactory::fromGlobals()->withAttribute('to', '/somewhere'),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(RedirectResponse::class, $resp);
    }
}
