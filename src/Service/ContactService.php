<?php
declare(strict_types=1);

namespace Acelaya\Website\Service;

use AcMailer\Exception\EmailNotFoundException;
use AcMailer\Exception\InvalidArgumentException;
use AcMailer\Exception\MailException;
use AcMailer\Service\MailServiceInterface;

class ContactService implements ContactServiceInterface
{
    public const TEMPLATE = 'Acelaya::emails/contact';

    /**
     * @var MailServiceInterface
     */
    private $mailService;

    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Sends the email and returns the result
     *
     * @param array $messageData
     * @return bool
     * @throws MailException
     * @throws InvalidArgumentException
     * @throws EmailNotFoundException
     */
    public function send(array $messageData): bool
    {
        $result = $this->mailService->send('contact', [
            'reply_to' => $messageData['email'],
            'template_params' => $messageData,
        ]);
        return $result->isValid();
    }
}
