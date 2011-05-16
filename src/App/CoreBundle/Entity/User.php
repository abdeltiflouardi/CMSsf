<?php

namespace App\CoreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\Role\Role;

/**
 * App\CoreBundle\Entity\User
 */
class User implements UserInterface, \Serializable {

    /**
     * @var string $username
     */
    private $username;
    /**
     * @var string $password
     */
    private $password;
    /**
     * @var boolean $enabled
     */
    private $enabled;
    /**
     * @var datetime $createdAt
     */
    private $createdAt;
    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;
    /**
     * @var integer $id
     */
    private $id;
    /**
     * @var App\CoreBundle\Entity\Team
     */
    private $team;

    public function __construct() {
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Add team
     *
     * @param App\CoreBundle\Entity\Team $team
     */
    public function addTeam(\App\CoreBundle\Entity\Team $team) {
        $this->team[] = $team;
    }

    /**
     * Get team
     *
     * @return Doctrine\Common\Collections\Collection $team
     */
    public function getTeam() {
        return $this->team;
    }

    /*
     * 
     */

    public function getSalt() {
        return mb_substr(md5($this->getUsername()), 3, 3);
    }

    public function eraseCredentials() {
        return true;
    }

    public function equals(UserInterface $user) {
        if ($this->getUsername() != $user->getUsername()) {
            return false;
        }

        return true;
    }

    public function getRoles() {
        $roles = array();
        foreach ($this->getTeam() as $team)
            $roles[] = new Role($team->getRole());

        return $roles;
    }

    public function serialize() {
        return serialize(
                array(
                    $this->getUsername()
                )
        );
    }

    public function unserialize($serialized) {

        $arr = unserialize($serialized);
        $this->setUsername($arr[0]);
    }

}