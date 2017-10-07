<?php
declare(strict_types=1);
namespace Acelaya\Website\Service;

use Acelaya\Website\Options\MailOptions;
use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Swift_Mailer;
use Zend\Expressive\Template\TemplateRendererInterface;

class ContactService implements ContactServiceInterface
{
    const TEMPLATE = 'Acelaya::emails/contact';

    /**
     * @var Swift_Mailer
     */
    protected $mailer;
    /**
     * @var TemplateRendererInterface
     */
    protected $renderer;
    /**
     * @var MailOptions
     */
    protected $options;

    /**
     * ContactService constructor.
     * @param Swift_Mailer $mailer
     * @param TemplateRendererInterface $renderer
     * @param MailOptions $options
     *
     * @Inject({Swift_Mailer::class, TemplateRendererInterface::class, MailOptions::class})
     */
    public function __construct(Swift_Mailer $mailer, TemplateRendererInterface $renderer, MailOptions $options)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->options = $options;
    }

    /**
     * Sends the email and returns the result
     *
     * @param array $messageData
     * @return bool
     */
    public function send(array $messageData): bool
    {
        $result = $this->mailer->send($this->createMessage($messageData));
        return $result === 1;
    }

    /**
     * @param array $messageData
     * @return \Swift_Mime_MimePart
     */
    private function createMessage(array $messageData): \Swift_Mime_MimePart
    {
        return \Swift_Message::newInstance($this->options->getSubject())
                             ->setTo($this->options->getTo())
                             ->setFrom($this->options->getFrom())
                             ->setReplyTo($messageData['email'])
                             ->setBody($this->composeBody($messageData), 'text/html');
    }

    /**
     * @param array $messageData
     * @return string
     */
    private function composeBody(array $messageData): string
    {
        return $this->renderer->render(self::TEMPLATE, $messageData);
    }
}
