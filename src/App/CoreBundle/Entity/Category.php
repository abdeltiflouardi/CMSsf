<?php

namespace App\CoreBundle\Entity;

/**
 * App\CoreBundle\Entity\Category
 */
class Category {

    /**
     * @var string $name
     */
    private $name;
    /**
     * @var integer $id
     */
    private $id;

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

}