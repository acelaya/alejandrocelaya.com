<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Action;

use Acelaya\Website\Action\Contact;
use Acelaya\Website\Form\ContactFilter;
use Acelaya\Website\Service\ContactService;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response as RecaptchaResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;
use Zend\Expressive\Template\TemplateRendererInterface;

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
        ContactFilter::RECAPTCHA => 'good',
    ];

    public function setUp()
    {
        $this->session = new \ArrayObject();

        $service = $this->prophesize(ContactService::class);
        $service->send($this->fullData)->willReturn(true);

        $this->renderer = $this->prophesize(TemplateRendererInterface::class);
        $this->renderer->render(Argument::cetera())->will(function (array $args) {
            $content = <<<EOF
<p class="content">Content</p>
EOF;
            $templateParams = array_pop($args);

            if (isset($templateParams['errors'])) {
                $content = '<p class="error-message">Error</p>' . PHP_EOL . $content;
            } elseif (isset($templateParams['success'])) {
                $content = '<p class="success-message">Success</p>' . PHP_EOL . $content;
            }

            return $content;
        });

        $recaptcha = $this->prophesize(ReCaptcha::class);
        $recaptcha->verify('good')->willReturn(new RecaptchaResponse(true));
        $recaptcha->verify('bad')->willReturn(new RecaptchaResponse(false));

        $this->contact = new Contact(
            $this->renderer->reveal(),
            $service->reveal(),
            new ContactFilter($recaptcha->reveal()),
            $this->session
        );
    }

    public function testGetRequestJustReturnsTheTemplate()
    {
        $request = (new ServerRequest([], [], null, 'GET'))->withAttribute('template', 'Acelaya::contact');
        $resp = $this->contact->dispatch($request, $this->prophesize(DelegateInterface::class)->reveal());
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
        $resp = $this->contact->dispatch($request, $this->prophesize(DelegateInterface::class)->reveal());
        $this->assertInstanceOf(RedirectResponse::class, $resp);
        $this->assertEquals(['/foo/bar'], $resp->getHeader('Location'));

        $this->assertTrue($this->session->offsetExists(Contact::PRG_DATA));
    }

    public function testInvalidPostDataReturnsErrorResponse()
    {
        $request = (new ServerRequest([], [], null, 'GET'))->withAttribute('template', 'Acelaya::contact');
        $this->session->offsetSet(Contact::PRG_DATA, []);
        $resp = $this->contact->dispatch($request, $this->prophesize(DelegateInterface::class)->reveal());
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
        $request = (new ServerRequest([], [], null, 'GET'))->withAttribute('template', 'Acelaya::contact');
        $this->session->offsetSet(Contact::PRG_DATA, $this->fullData);
        $resp = $this->contact->dispatch($request, $this->prophesize(DelegateInterface::class)->reveal());
        $this->assertFalse($this->session->offsetExists(Contact::PRG_DATA));

        $this->assertInstanceOf(HtmlResponse::class, $resp);
        $document = new \DOMDocument();
        $document->loadHTML($resp->getBody()->__toString());
        // Get first paragraph
        $document = $document->documentElement->firstChild->firstChild;
        $this->assertEquals('success-message', $document->getAttribute('class'));
    }
}
