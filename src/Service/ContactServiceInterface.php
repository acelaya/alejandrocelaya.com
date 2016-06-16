<?php
namespace Acelaya\Website\Service;

interface ContactServiceInterface
{
    /**
     * Sends the email and returns the result
     *
     * @param array $messageData
     * @return bool
     */
    public function send(array $messageData): bool;
}
