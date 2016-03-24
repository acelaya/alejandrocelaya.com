<?php
namespace AcelayaTest\Website\Service;

use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service\ContactService;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Expressive\Twig\TwigRenderer;

class ContactServiceTest extends TestCase
{
    /**
     * @var ContactService
     */
    protected $service;
    /**
     * @var array
     */
    protected $messageData = [
        'email' => 'alejandro@alejandrocelaya.com'
    ];
    /**
     * @var array
     */
    protected $config = [
        'subject' => 'foo',
        'to' => 'alejandro@alejandrocelaya.com',
        'from' => 'alejandro@alejandrocelaya.com',
    ];

    public function setUp()
    {
        $mailer = new \Swift_Mailer(new \Swift_NullTransport());
        $renderer = new TwigRenderer(new \Twig_Environment(new \Twig_Loader_Array([
            ContactService::TEMPLATE => '<p>{{ email }}</p>'
        ])));

        $this->service = new ContactService($mailer, $renderer, new MailOptions($this->config));
    }

    public function testSend()
    {
        $result = $this->service->send($this->messageData);
        $this->assertTrue($result);
    }
}
