<?php

namespace App\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use App\CoreBundle\Entity\Tag;

class Tags
{

    protected $request;
    protected $em;
    protected $namespace = 'AppCoreBundle';

    public function __construct(Request $request, EntityManager $em)
    {
        $this->request = $request;
        $this->em      = $em;
    }

    /**
     * @return array $tags
     */
    public function parseTags()
    {
        $posted_post = $this->request->get('post');

        if (!$posted_post) {
            return null;
        }

        $tags = preg_split('/[,;]/', $posted_post['words']);
        $tags = array_map('trim', $tags);
        $tags = array_map('strtolower', $tags);
        return $tags;
    }

    public function addTags($id)
    {
        $tags = $this->parseTags();
        if (!$tags) {
            return null;
        }

        $post = $this->em->find($this->namespace . ':Post', $id);
        foreach ($tags as $tag_name) {
            $tag = $this->em->getRepository($this->namespace . ':Tag')->findOneByName($tag_name);
            if (!$tag) {
                $tag = new Tag();
                $tag->setName($tag_name);
                $this->em->persist($tag);
                $this->em->flush();
            }

            $post->getTag()->add($tag);
            $tag->getPost()->add($post);

            $em = $this->em;
            $em->persist($post);
            $em->persist($tag);
            $em->flush();
        }
    }

    public function editTags($id)
    {
        $tags = $this->parseTags();
        if (!$tags) {
            return null;
        }

        $post = $this->em->find($this->namespace . ':Post', $id);
        foreach ($post->getTag() as $tag) {
            $post->getTag()->removeElement($tag);
            $tag->getPost()->removeElement($post);

            $this->em->persist($post);
            $this->em->persist($tag);
            $this->em->flush();
        }

        $this->addTags($id);
    }
}
