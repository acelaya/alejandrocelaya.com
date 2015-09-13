<?php
namespace AcelayaTest\Website\Options;

use Acelaya\Website\Options\SmtpOptions;
use PHPUnit_Framework_TestCase as TestCase;

class SmtpOptionsTest extends TestCase
{
    /**
     * @var SmtpOptions
     */
    protected $options;

    public function testData()
    {
        $data = [
            'server' => 'mail.server.com',
            'ssl' => false,
            'port' => 25,
            'username' => 'username',
            'password' => 'password',
        ];
        $this->options = new SmtpOptions($data);

        $this->assertEquals($data['server'], $this->options->getServer());
        $this->assertEquals($data['ssl'], $this->options->getSsl());
        $this->assertEquals($data['port'], $this->options->getPort());
        $this->assertEquals($data['username'], $this->options->getUsername());
        $this->assertEquals($data['password'], $this->options->getPassword());
    }
}
