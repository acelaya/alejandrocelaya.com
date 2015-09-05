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
        $this->template = new Template($templates);
    }

    public function testDispatch()
    {
        $request = (new ServerRequest())->withAttribute('template', 'foo.html.twig');
        $resp = $this->template->dispatch($request, new Response());
        $this->assertEquals('<h1>Hello!!</h1>', $resp->getBody()->__toString());
    }
}
