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
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param string $server
     * @return SmtpOptions
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return string
     */
    public function getSsl()
    {
        return $this->ssl;
    }

    /**
     * @param string $ssl
     * @return SmtpOptions
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return SmtpOptions
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return SmtpOptions
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return SmtpOptions
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}
