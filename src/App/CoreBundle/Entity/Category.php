<?php

namespace App\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\CoreBundle\Entity\Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var integer $position
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var Post
     * 
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    private $posts;

    /**
     * @var Category
     * 
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $subCategories;

    public function __construct()
    {
        $this->posts         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subCategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return integer $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set parent
     *
     * @param App\CoreBundle\Entity\Category $parent
     */
    public function setParent(\App\CoreBundle\Entity\Category $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return App\CoreBundle\Entity\Category $parent
     */
    public function getParent()
    {
        return $this->parent;
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
