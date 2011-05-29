<?php

namespace App\CoreBundle\Entity;

/**
 * App\CoreBundle\Entity\Comment
 */
class Comment
{
    /**
     * @var text $comment
     */
    private $comment;

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
     * @var App\CoreBundle\Entity\Post
     */
    private $post;

    /**
     * @var App\CoreBundle\Entity\User
     */
    private $user;


    /**
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Set post
     *
     * @param App\CoreBundle\Entity\Post $post
     */
    public function setPost(\App\CoreBundle\Entity\Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return App\CoreBundle\Entity\Post $post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set user
     *
     * @param App\CoreBundle\Entity\User $user
     */
    public function setUser(\App\CoreBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return App\CoreBundle\Entity\User $user
     */
    public function getUser()
    {
        return $this->user;
    }
}