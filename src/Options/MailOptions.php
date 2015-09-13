<?php
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
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return SmtpOptions|null
     */
    public function getSmtp()
    {
        return $this->smtp;
    }

    /**
     * @param SmtpOptions|array $smtp
     * @return $this
     */
    public function setSmtp($smtp)
    {
        $this->smtp = is_array($smtp) ? new SmtpOptions($smtp) : $smtp;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }
}
