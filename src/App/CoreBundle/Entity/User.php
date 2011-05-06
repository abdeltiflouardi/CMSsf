<?php

namespace App\CoreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\Role\Role;

/**
 * App\CoreBundle\Entity\User
 *
 * @orm:Table(name="user")
 * @orm:Entity
 */
class User implements UserInterface, \Serializable {

    /**
     * @var integer $id
     *
     * @orm:Column(name="id", type="integer", nullable=false)
     * @orm:Id
     * @orm:GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string $username
     *
     * @orm:Column(name="username", type="string", length=100, nullable=true)
     */
    private $username;
    /**
     * @var string $password
     *
     * @orm:Column(name="password", type="string", length=100, nullable=true)
     */
    private $password;
    /**
     * @var datetime $createdAt
     *
     * @orm:Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;
    /**
     * @var datetime $updatedAt
     *
     * @orm:Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @var Team
     *
     * @orm:ManyToOne(targetEntity="Team")
     * @orm:JoinColumns({
     *   @orm:JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this->id;
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
     * Set team
     *
     * @param App\CoreBundle\Entity\Team $team
     */
    public function setTeam(\App\CoreBundle\Entity\Team $team) {
        $this->team = $team;
    }

    /**
     * Get team
     *
     * @return App\CoreBundle\Entity\Team $team
     */
    public function getTeam() {
        return $this->team;
    }

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
        return array(new Role('ROLE_ADMIN'));
    }
    
    public function serialize()
    {
      return serialize(
           array(
                $this->getUsername()
           )
      );
    }

    public function unserialize($serialized)
    {

      $arr = unserialize($serialized);
      $this->setUsername($arr[0]);
    }    

}
