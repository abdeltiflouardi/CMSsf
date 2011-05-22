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
     * @var App\CoreBundle\Entity\Category
     */
    private $parent;
    /**
     * @var integer $position
     */
    private $position;
    /**
     * @var App\CoreBundle\Entity\Post
     */
    private $posts;
    /**
     * @var App\CoreBundle\Entity\Category
     */
    private $subCategories;
    
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subCategories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set parent
     *
     * @param App\CoreBundle\Entity\Category $parent
     */
    public function setParent(\App\CoreBundle\Entity\Category $parent) {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return App\CoreBundle\Entity\Category $parent
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return integer $position
     */
    public function getPosition() {
        return $this->position;
    }
    
    /**
     * Add posts
     *
     * @param App\CoreBundle\Entity\Post $posts
     */
    public function addPosts(\App\CoreBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection $posts
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add subCategories
     *
     * @param App\CoreBundle\Entity\Category $subCategories
     */
    public function addSubCategories(\App\CoreBundle\Entity\Category $subCategories)
    {
        $this->subCategories[] = $subCategories;
    }

    /**
     * Get subCategories
     *
     * @return Doctrine\Common\Collections\Collection $subCategories
     */
    public function getSubCategories()
    {
        return $this->subCategories;
    }
}