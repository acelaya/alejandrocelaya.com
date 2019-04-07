<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Service;

use Acelaya\Website\Service\ContactService;
use AcMailer\Model\Email;
use AcMailer\Result\MailResult;
use AcMailer\Service\MailServiceInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class ContactServiceTest extends TestCase
{
    /**
     * @var array
     */
    private const MESSAGE_DATA = [
        'email' => 'alejandro@alejandrocelaya.com',
    ];

    /** @var ContactService */
    protected $service;
    /** @var ObjectProphecy */
    protected $mailService;

    protected function setUp(): void
    {
        $this->mailService = $this->prophesize(MailServiceInterface::class);
        $this->service = new ContactService($this->mailService->reveal());
    }

    public function testSend()
    {
        $this->mailService->send(Argument::cetera())->willReturn(new MailResult(new Email()))
                                                    ->shouldBeCalledTimes(1);

        $result = $this->service->send(self::MESSAGE_DATA);

        $this->assertTrue($result);
    }
}
