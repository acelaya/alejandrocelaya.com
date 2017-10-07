<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Service;

use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Service\ContactService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Expressive\Template\TemplateRendererInterface;

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
        'email' => 'alejandro@alejandrocelaya.com',
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
        $renderer = $this->prophesize(TemplateRendererInterface::class);
        $renderer->render(ContactService::TEMPLATE, Argument::cetera())->willReturn('<p>{{ email }}</p>');

        $this->service = new ContactService($mailer, $renderer->reveal(), new MailOptions($this->config));
    }

    public function testSend()
    {
        $result = $this->service->send($this->messageData);
        $this->assertTrue($result);
    }
}
