<?php
declare(strict_types=1);
namespace AcelayaTest\Website\Options;

use Acelaya\Website\Options\MailOptions;
use Acelaya\Website\Options\SmtpOptions;
use PHPUnit\Framework\TestCase;

class MailOptionsTest extends TestCase
{
    /**
     * @var MailOptions
     */
    protected $options;

    public function testData()
    {
        $data = [
            'smtp' => [],
            'from' => 'from',
            'to' => 'to',
            'subject' => 'subject',
        ];
        $this->options = new MailOptions($data);

        $this->assertInstanceOf(SmtpOptions::class, $this->options->getSmtp());
        $this->assertEquals($data['from'], $this->options->getFrom());
        $this->assertEquals($data['to'], $this->options->getTo());
        $this->assertEquals($data['subject'], $this->options->getSubject());
    }
}
