<?php

namespace App\CoreBundle\Entity;

/**
 * App\CoreBundle\Entity\Team
 */
class Team {

    /**
     * @var string $name
     */
    private $name;
    /**
     * @var integer $id
     */
    private $id;
    /**
     * @var App\CoreBundle\Entity\User
     */
    private $user;

    public function __construct() {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName() {
        return $this->name;
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
     * Add user
     *
     * @param App\CoreBundle\Entity\User $user
     */
    public function addUser(\App\CoreBundle\Entity\User $user) {
        $this->user[] = $user;
    }

    /**
     * Get user
     *
     * @return Doctrine\Common\Collections\Collection $user
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @var string $role
     */
    private $role;

    /**
     * Set role
     *
     * @param string $role
     */
    public function setRole($role) {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return string $role
     */
    public function getRole() {
        return $this->role;
    }

}