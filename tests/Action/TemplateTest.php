<?php
namespace AcelayaTest\Website\Action;

use Acelaya\Website\Action\Template;
use Doctrine\Common\Cache\Cache;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Twig\TwigRenderer;

class TemplateTest extends TestCase
{
    /**
     * @var Template
     */
    protected $template;
    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var array
     */
    protected $templatesContentMap = [
        'foo.html.twig' => '<h1>{{ "Hello!!" }}</h1>',
        'errors/404.html.twig' => 'Error'
    ];

    public function setUp()
    {
        $templates = new TwigRenderer(new \Twig_Environment(new \Twig_Loader_Array($this->templatesContentMap)));
        $this->template = new Template($templates);
    }

    public function testDispatch()
    {
        $request = (new ServerRequest())->withAttribute('template', 'foo.html.twig');
        $resp = $this->template->dispatch($request, $this->prophesize(DelegateInterface::class)->reveal());
        $this->assertEquals('<h1>Hello!!</h1>', $resp->getBody()->__toString());
        $this->assertEquals(200, $resp->getStatusCode());
    }

    public function testDispatchWithoutTemplate()
    {
        $resp = $this->template->dispatch(new ServerRequest(), $this->prophesize(DelegateInterface::class)->reveal());
        $this->assertEquals('Error', $resp->getBody()->__toString());
        $this->assertEquals(404, $resp->getStatusCode());
    }

    public function testDispatchAndProcessAreTheSame()
    {
        $request = (new ServerRequest())->withAttribute('template', 'foo.html.twig');
        $firstResp = $this->template->dispatch($request, $this->prophesize(DelegateInterface::class)->reveal());
        $this->assertEquals('<h1>Hello!!</h1>', $firstResp->getBody()->__toString());
        $this->assertEquals(200, $firstResp->getStatusCode());

        $secondResp = $this->template->process($request, $this->prophesize(DelegateInterface::class)->reveal());
        $this->assertEquals('<h1>Hello!!</h1>', $secondResp->getBody()->__toString());
        $this->assertEquals(200, $secondResp->getStatusCode());
    }
}
