<?php

namespace App\CoreBundle\Request;

use Symfony\Component\Validator\Constraints as Assert;

class InitPassword {

    /**
     * @var string $oldPassword
     *
     * @Assert\NotBlank()
     */
     private $oldPassword;

    /**
     * @var string $newPassword
     *
     * @Assert\NotBlank()
     */
     private $newPassword;    

    /**
     * Set oldPassword
     *
     * @param string $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * Get oldPassword
     *
     * @return string $oldPassword
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }     

    /**
     * Set newPassword
     *
     * @param string $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * Get newPassword
     *
     * @return string $newPassword
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }


}
