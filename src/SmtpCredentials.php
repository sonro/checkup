<?php

namespace App;

class SmtpCredentials
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $server;

    /**
     * @var int
     */
    private $port;

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
     * Set the value of username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
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
     * Set the value of password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
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
     * Set the value of server.
     *
     * @param string $server
     *
     * @return self
     */
    public function setServer(string $server)
    {
        $this->server = $server;

        return $this;
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
     * Set the value of port.
     *
     * @param int $port
     *
     * @return self
     */
    public function setPort(int $port)
    {
        $this->port = $port;

        return $this;
    }
}
