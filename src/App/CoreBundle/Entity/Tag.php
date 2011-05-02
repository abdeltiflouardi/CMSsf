<?php

namespace App\CoreBundle\Entity;

/**
 * App\CoreBundle\Entity\Tag
 *
 * @orm:Table(name="tag")
 * @orm:Entity
 */
class Tag
{
    /**
     * @var integer $id
     *
     * @orm:Column(name="id", type="integer", nullable=false)
     * @orm:Id
     * @orm:GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @orm:Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var Post
     *
     * @orm:ManyToMany(targetEntity="Post", mappedBy="tag")
     */
    private $post;

    public function __construct()
    {
        $this->post = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add post
     *
     * @param App\CoreBundle\Entity\Post $post
     */
    public function addPost(\App\CoreBundle\Entity\Post $post)
    {
        $this->post[] = $post;
    }

    /**
     * Get post
     *
     * @return Doctrine\Common\Collections\Collection $post
     */
    public function getPost()
    {
        return $this->post;
    }
}