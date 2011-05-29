<?php

namespace App\CoreBundle\Entity;

/**
 * App\CoreBundle\Entity\Post
 */
class Post {

    /**
     * @var string $title
     */
    private $title;
    /**
     * @var text $body
     */
    private $body;
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
     * @var App\CoreBundle\Entity\Category
     */
    private $category;
    /**
     * @var App\CoreBundle\Entity\User
     */
    private $user;
    /**
     * @var App\CoreBundle\Entity\Tag
     */
    private $tag;
    /**
     * @var App\CoreBundle\Entity\Comment
     */
    private $comments;
    /**
     * Words tags
     */
    private $words;

    public function __construct() {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param text $body
     */
    public function setBody($body) {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text $body
     */
    public function getBody() {
        return $this->body;
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
     * Set category
     *
     * @param App\CoreBundle\Entity\Category $category
     */
    public function setCategory(\App\CoreBundle\Entity\Category $category) {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return App\CoreBundle\Entity\Category $category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param App\CoreBundle\Entity\User $user
     */
    public function setUser(\App\CoreBundle\Entity\User $user) {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return App\CoreBundle\Entity\User $user
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Add tag
     *
     * @param App\CoreBundle\Entity\Tag $tag
     */
    public function addTag(\App\CoreBundle\Entity\Tag $tag) {
        $this->tag[] = $tag;
    }

    /**
     * Get tag
     *
     * @return Doctrine\Common\Collections\Collection $tag
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * set $words
     */
    public function setWords($words) {
        $this->words = $words;
    }

    /**
     * get words
     */
    public function getWords() {
        $tags = array();
        foreach ($this->getTag() as $tag)
            $tags[] = $tag->getName();
        return implode(',', $tags);
    }

    /**
     * Add comments
     *
     * @param App\CoreBundle\Entity\Comment $comments
     */
    public function addComments(\App\CoreBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection $comments
     */
    public function getComments()
    {
        return $this->comments;
    }
}