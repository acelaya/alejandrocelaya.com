<?php
namespace Acelaya\Website\Options;

use Zend\Stdlib\AbstractOptions;

class SmtpOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $server = 'smtp.gmail.com';
    /**
     * @var string
     */
    protected $ssl = 'tls';
    /**
     * @var int
     */
    protected $port = 587;
    /**
     * @var string
     */
    protected $username = '';
    /**
     * @var string
     */
    protected $password = '';

    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * @param string $server
     * @return SmtpOptions
     */
    public function setServer(string $server): SmtpOptions
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return string
     */
    public function getSsl(): string
    {
        return $this->ssl;
    }

    /**
     * @param string $ssl
     * @return SmtpOptions
     */
    public function setSsl(string $ssl): SmtpOptions
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return SmtpOptions
     */
    public function setPort(int $port): SmtpOptions
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return SmtpOptions
     */
    public function setUsername(string $username): SmtpOptions
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return SmtpOptions
     */
    public function setPassword(string $password): SmtpOptions
    {
        $this->password = $password;
        return $this;
    }
}
