<?php
declare(strict_types=1);

namespace Acelaya\Website\Options;

use Zend\Stdlib\AbstractOptions;

class MailOptions extends AbstractOptions
{
    /**
     * @var SmtpOptions
     */
    protected $smtp = null;
    /**
     * @var string
     */
    protected $from = '';
    /**
     * @var string
     */
    protected $to = '';
    /**
     * @var string
     */
    protected $subject = 'Alejandro Celaya | Contact form';

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this|MailOptions
     */
    public function setSubject(string $subject): MailOptions
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return SmtpOptions|null
     */
    public function getSmtp(): SmtpOptions
    {
        return $this->smtp;
    }

    /**
     * @param SmtpOptions|array $smtp
     * @return $this|MailOptions
     */
    public function setSmtp($smtp): MailOptions
    {
        $this->smtp = is_array($smtp) ? new SmtpOptions($smtp) : $smtp;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return $this|MailOptions
     */
    public function setFrom(string $from): MailOptions
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return $this|MailOptions
     */
    public function setTo(string $to): MailOptions
    {
        $this->to = $to;
        return $this;
    }
}
