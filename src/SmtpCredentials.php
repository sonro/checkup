<?php

namespace Sonro\Checkup;

use JMS\Serializer\Annotation\Type;

class SmtpCredentials
{
    /**
     * @var string
     * @Type("string")
     */
    private $username;

    /**
     * @var string
     * @Type("string")
     */
    private $password;

    /**
     * @var string
     * @Type("string")
     */
    private $server;

    /**
     * @var int
     * @Type("int")
     */
    private $port;

    /**
     * @var string
     * @Type("string")
     */
    private $secureType;

    /**
     * Get the value of username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the value of password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of server.
     *
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Get the value of port.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Get the value of secureType.
     *
     * @return string
     */
    public function getSecureType()
    {
        return $this->secureType;
    }
}
