<?php
namespace AcelayaTest\Website\Action;

use Acelaya\Website\Action\Contact;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Service\ContactService;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response as RecaptchaResponse;
use Symfony\Component\Intl\Data\Util\ArrayAccessibleResourceBundle;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Session\Container;

class ContactTest extends TestCase
{
    /**
     * @var Contact
     */
    protected $contact;
    /**
     * @var TemplateRendererInterface
     */
    protected $renderer;
    /**
     * @var \ArrayAccess;
     */
    protected $session;
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
        $this->session = new \ArrayObject();

        $service = $this->prophesize(ContactService::class);
        $service->send($this->fullData)->willReturn(true);

        $this->renderer = new TwigRenderer(new \Twig_Environment(new \Twig_Loader_Array([
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

        $this->contact = new Contact(
            $this->renderer,
            $service->reveal(),
            new ContactFilter($recaptcha->reveal()),
            $this->session
        );
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

    public function testPostRequestSavesPostDataInSessionAndReturnsRedirect()
    {
        $request = ServerRequestFactory::fromGlobals(null, null, $this->fullData)
                                       ->withMethod('POST')
                                       ->withUri(new Uri('/foo/bar'));

        $this->assertFalse($this->session->offsetExists(Contact::PRG_DATA));
        $resp = $this->contact->dispatch($request, new Response());
        $this->assertInstanceOf(RedirectResponse::class, $resp);
        $this->assertEquals(['/foo/bar'], $resp->getHeader('Location'));

        $this->assertTrue($this->session->offsetExists(Contact::PRG_DATA));
    }

    public function testInvalidPostDataReturnsErrorResponse()
    {
        $request = (new ServerRequest([], [], null, 'GET'))->withAttribute('template', 'contact.html.twig');
        $this->session->offsetSet(Contact::PRG_DATA, []);
        $resp = $this->contact->dispatch($request, new Response());
        $this->assertFalse($this->session->offsetExists(Contact::PRG_DATA));

        $this->assertInstanceOf(HtmlResponse::class, $resp);
        $document = new \DOMDocument();
        $document->loadHTML($resp->getBody()->__toString());
        // Get first paragraph
        $document = $document->documentElement->firstChild->firstChild;
        $this->assertEquals('error-message', $document->getAttribute('class'));
    }

    public function testValidPostSendsContactAndReturnsSuccess()
    {
        $request = (new ServerRequest([], [], null, 'GET'))->withAttribute('template', 'contact.html.twig');
        $this->session->offsetSet(Contact::PRG_DATA, $this->fullData);
        $resp = $this->contact->dispatch($request, new Response());
        $this->assertFalse($this->session->offsetExists(Contact::PRG_DATA));

        $this->assertInstanceOf(HtmlResponse::class, $resp);
        $document = new \DOMDocument();
        $document->loadHTML($resp->getBody()->__toString());
        // Get first paragraph
        $document = $document->documentElement->firstChild->firstChild;
        $this->assertEquals('success-message', $document->getAttribute('class'));
    }
}
