<?php
namespace Acelaya\Website\Service;

use Zend\Expressive\Template\TemplateInterface;

class ContactService implements ContactServiceInterface
{
    const TEMPLATE = 'emails/contact.html.twig';

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;
    /**
     * @var TemplateInterface
     */
    protected $renderer;
    /**
     * @var array
     */
    protected $config;

    public function __construct(\Swift_Mailer $mailer, TemplateInterface $renderer, array $config)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->config = $config;
    }

    /**
     * Sends the email and returns the result
     *
     * @param array $messageData
     * @return bool
     */
    public function send(array $messageData)
    {
        $result = $this->mailer->send($this->createMessage($messageData));
        return $result === 1;
    }

    private function createMessage(array $messageData)
    {
        return \Swift_Message::newInstance($this->config['subject'])
                             ->setTo($this->config['to'])
                             ->setFrom($this->config['from'])
                             ->setReplyTo($messageData['email'])
                             ->setBody($this->composeBody($messageData), 'text/html');
    }

    private function composeBody(array $messageData)
    {
        return $this->renderer->render(self::TEMPLATE, $messageData);
    }
}
