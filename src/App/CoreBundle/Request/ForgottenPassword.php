<?php

namespace App\CoreBundle\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ForgottenPassword
{

    /**
     * @var string $email
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }
}
