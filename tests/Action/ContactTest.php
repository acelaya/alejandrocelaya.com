<?php
namespace AcelayaTest\Website\Action;

use Acelaya\Website\Action\Contact;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Service\ContactService;
use PHPUnit_Framework_TestCase as TestCase;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response as RecaptchaResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Expressive\Template\TemplateInterface;
use Zend\Expressive\Template\Twig;

class ContactTest extends TestCase
{
    /**
     * @var Contact
     */
    protected $contact;
    /**
     * @var TemplateInterface
     */
    protected $renderer;
    /**
     * @var array
     */
    protected $fullData = [
        ContactFilter::NAME => 'Alejandro Celaya',
        ContactFilter::EMAIL => 'alejandro@alejandrocelaya.com',
        ContactFilter::SUBJECT => 'My contact',
        ContactFilter::COMMENTS => 'Hello!!',
        ContactFilter::RECAPTCHA => 'good'
    ];

    public function setUp()
    {
        $service = $this->prophesize(ContactService::class);
        $service->send($this->fullData)->willReturn(true);

        $this->renderer = new Twig(new \Twig_Environment(new \Twig_Loader_Array([
            'contact.html.twig' => <<<EOF
{% if errors is defined %}
    <p class="error-message">Error</p>
{% elseif success is defined %}
    <p class="success-message">Success</p>
{% endif %}
<p class="content">Content</p>
EOF
        ])));

        $recaptcha = $this->prophesize(ReCaptcha::class);
        $recaptcha->verify('good')->willReturn(new RecaptchaResponse(true));
        $recaptcha->verify('bad')->willReturn(new RecaptchaResponse(false));

        $this->contact = new Contact($this->renderer, $service->reveal(), new ContactFilter($recaptcha->reveal()));
    }

    public function testGetRequestJustReturnsTheTemplate()
    {
        $request = (new ServerRequest([], [], null, 'GET'))->withAttribute('template', 'contact.html.twig');
        $resp = $this->contact->dispatch($request, new Response());
        $this->assertInstanceOf(HtmlResponse::class, $resp);
        $document = new \DOMDocument();
        $document->loadHTML($resp->getBody()->__toString());
        // Get first paragraph
        $document = $document->documentElement->firstChild->firstChild;
        $this->assertEquals('content', $document->getAttribute('class'));
    }

    public function testInvalidPostDataReturnsErrorResponse()
    {
        $request = ServerRequestFactory::fromGlobals(null, null, [])
                                       ->withMethod('POST')
                                       ->withAttribute('template', 'contact.html.twig');
        $resp = $this->contact->dispatch($request, new Response());
        $this->assertInstanceOf(HtmlResponse::class, $resp);
        $document = new \DOMDocument();
        $document->loadHTML($resp->getBody()->__toString());
        // Get first paragraph
        $document = $document->documentElement->firstChild->firstChild;
        $this->assertEquals('error-message', $document->getAttribute('class'));
    }

    public function testValidPostSendsContactAndReturnsSuccess()
    {
        $request = ServerRequestFactory::fromGlobals(null, null, $this->fullData)
            ->withMethod('POST')
            ->withAttribute('template', 'contact.html.twig');
        $resp = $this->contact->dispatch($request, new Response());
        $this->assertInstanceOf(HtmlResponse::class, $resp);
        $document = new \DOMDocument();
        $document->loadHTML($resp->getBody()->__toString());
        // Get first paragraph
        $document = $document->documentElement->firstChild->firstChild;
        $this->assertEquals('success-message', $document->getAttribute('class'));
    }
}
