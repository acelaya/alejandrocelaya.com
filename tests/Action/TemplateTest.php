<?php
namespace AcelayaTest\Website\Action;

use Acelaya\Website\Action\Template;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Template\Twig;

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
        'foo.html.twig' => '<h1>{{ "Hello!!" }}</h1>'
    ];

    public function setUp()
    {
        $templates = new Twig(new \Twig_Environment(new \Twig_Loader_Array($this->templatesContentMap)));
        $this->cache = new ArrayCache();
        $this->template = new Template($templates, $this->cache);
    }

    public function testDispatch()
    {
        $request = (new ServerRequest())->withAttribute('template', 'foo.html.twig');
        $content = $this->template->dispatch($request, new Response());
        $this->assertEquals('<h1>Hello!!</h1>', $content);
    }

    public function testCacheIsSet()
    {
        $request = (new ServerRequest([], [], '/foo'))->withAttribute('template', 'foo.html.twig');

        $this->assertFalse($this->cache->contains('/foo'));
        $response = $this->template->__invoke($request, new Response());
        $this->assertTrue($this->cache->contains('/foo'));
        $this->assertEquals('<h1>Hello!!</h1>', $this->cache->fetch('/foo'));
    }
}
