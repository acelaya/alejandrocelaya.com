<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Action;

use Acelaya\Website\Action\Template;
use Doctrine\Common\Cache\Cache;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Template\TemplateRendererInterface;

class TemplateTest extends TestCase
{
    private const TEMPLATES_CONTENT_MAP = [
        'Acelaya::foo' => '<h1>Hello!!</h1>',
        'Acelaya::errors/404' => 'Error',
    ];

    /**
     * @var Template
     */
    protected $template;
    /**
     * @var Cache
     */
    protected $cache;

    public function setUp()
    {
        $templates = $this->prophesize(TemplateRendererInterface::class);
        $templates->render(Argument::cetera())->will(function (array $args) {
            $temnplateName = array_shift($args);
            return self::TEMPLATES_CONTENT_MAP[$temnplateName];
        });
        $this->template = new Template($templates->reveal());
    }

    public function testDispatch()
    {
        $request = (new ServerRequest())->withAttribute('template', 'Acelaya::foo');
        $resp = $this->template->dispatch($request);
        $this->assertEquals('<h1>Hello!!</h1>', $resp->getBody()->__toString());
        $this->assertEquals(200, $resp->getStatusCode());
    }

    public function testDispatchWithoutTemplate()
    {
        $resp = $this->template->dispatch(new ServerRequest());
        $this->assertEquals('Error', $resp->getBody()->__toString());
        $this->assertEquals(404, $resp->getStatusCode());
    }

    public function testDispatchAndProcessAreTheSame()
    {
        $request = (new ServerRequest())->withAttribute('template', 'Acelaya::foo');
        $firstResp = $this->template->dispatch($request);
        $this->assertEquals('<h1>Hello!!</h1>', $firstResp->getBody()->__toString());
        $this->assertEquals(200, $firstResp->getStatusCode());

        $secondResp = $this->template->handle($request);
        $this->assertEquals('<h1>Hello!!</h1>', $secondResp->getBody()->__toString());
        $this->assertEquals(200, $secondResp->getStatusCode());
    }
}
