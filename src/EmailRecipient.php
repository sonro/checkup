<?php

namespace App;

class EmailRecipient
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * Get the value of name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @param string|null $name
     *
     * @return self
     */
    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }
}
