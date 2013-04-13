<?php

namespace App\WebBundle\Controller;

class PostController extends WebBaseController
{

    public function indexAction()
    {
        /**
         * Select posts
         */
        $params = array();
        $this->searchByWord($params);
        $this->searchByCategory($params);
        $this->searchByTag($params);

        $params['order'] = 'a.updatedAt DESC';
        $posts           = $this->paginator('Post', $params);

        return $this->renderTpl('Post:index', compact('posts'));
    }

    public function showAction($post_id, $slug = null)
    {

        $post = $this->findOne('Post', $post_id);
        if (!$post) {
            return $this->notFound();
        }

        $comment      = new \App\CoreBundle\Entity\Comment();
        $form_comment = $this->getForm('Comment', $comment);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form_comment->bindRequest($request);

            $comment->setPost($post);
            $comment->setUser($this->getUser());

            if ($form_comment->isValid()) {
                $em = $this->getEm();
                $em->persist($comment);
                $em->flush();

                $this->makeAcl($comment);

                return $this->redirect($this->generateUrl('_post', compact('post_id', 'slug')));
            }
        }

        $form_comment = $form_comment->createView();

        // Related, Next & Previous
        $next_post     = $this->getNextPost($post);
        $previous_post = $this->getPreviousPost($post);
        $related_posts = $this->getRelatedPosts($post);

        $this->renderData(compact('post', 'form_comment', 'next_post', 'previous_post', 'related_posts'));
        return $this->renderTpl('Post:show');
    }

    private function getNextPost($post)
    {
        $id    = $post->getId();
        $query = $this->getRepo('Post')->createQueryBuilder('p')
                ->where('p.id > :post_id')
                ->setParameter('post_id', $id)
                ->orderBy('p.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery();
        return current($query->getResult());
    }

    private function getPreviousPost($post)
    {
        $id    = $post->getId();
        $query = $this->getRepo('Post')->createQueryBuilder('p')
                ->where('p.id < :post_id')
                ->setParameter('post_id', $id)
                ->orderBy('p.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery();
        return current($query->getResult());
    }

    private function getRelatedPosts($post)
    {
        $posts = array();
        foreach ($post->getTag() as $tag) {
            foreach ($tag->getPost() as $tag_post) {
                if ($post != $tag_post) {
                    $posts[$tag_post->getId()] = $tag_post;
                }

                if (count($posts) >= $this->container->getParameter('count.related.post')) {
                    break;
                }
            }

            if (count($posts) >= $this->container->getParameter('count.related.post')) {
                break;
            }
        }

        return $posts;
    }

    private function searchByWord(&$params)
    {
        $query_get = $this->get('request')->query;
        $q         = $query_get->get('q');
        if (!empty($q)) {
            $q               = strtolower($q);
            $params['where'] = "LOWER(a.title) like '%$q%'";
        }
    }

    private function searchByCategory(&$params)
    {
        $category_id = $this->get('request')->get('category_id');
        if (!empty($category_id)) {
            $categories = $this->getRepo('Category')->findBy(array('parent' => $category_id));
            if (!empty($categories)) {
                $ids = array();
                foreach ($categories as $category) {
                    $ids[] = $category->getId();
                }

                if (!empty($ids)) {
                    $ids             = implode(',', $ids);
                    $params['where'] = "a.category IN ($ids)";
                }
            } else {
                $params['where'] = "a.category = $category_id";
            }
        }
    }

    private function searchByTag(&$params)
    {
        $tag_id = $this->get('request')->get('tag_id');
        $tag    = $this->get('request')->get('tag');
        if (!empty($tag_id)) {
            $tag = $this->findOne("Tag", $tag_id);
            foreach ($tag->getPost() as $post) {
                $in[] = $post->getId();
            }
            $in              = implode(',', $in);
            $params['where'] = "a.id IN ($in)";
        }
    }
}
