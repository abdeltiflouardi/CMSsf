<?php

namespace App\AdminBundle\Controller;

use App\CoreBundle\Controller\ErrorController;

class PostController extends AdminBaseController {

    protected $_name = 'Post';
    
    public function __construct() {
    }

    public function indexAction() {
        $posts = $this->paginator($this->_name);
        return $this->renderTpl($this->_name . ':index', compact('posts'));
    }   

    public function showAction($id) {
	$post = $this->findOne($this->_name, $id);
        
	if (!$post)
		return $this->renderTpl ('Error:error', ErrorController::error (404), true);
        
	$tag_form = $this->getForm('TagList');
	$tag_form = $tag_form->createView();	

	$this->template = $this->get('twig');
	// append user variable
	$this->template->addGlobal('message', 'Info');

	return $this->renderTpl($this->_name . ':show', compact('post', 'tag_form'));
    }

    public function addTagAction($post_id) {
	$request = $this->get('request')->request->get('taglist');
	$tag_id = $request['tag'];

	$post = $this->findOne('Post', $post_id);
	$tag = $this->findOne('Tag', $tag_id);

	$post->getTag()->add($tag);
	$tag->getPost()->add($post);
	
        $em = $this->getEm();
        $em->persist($post);
        $em->persist($tag);
        $em->flush();
        return $this->redirect($this->generateUrl('_admin_post_show', array('id' => $post_id)));
    }

    public function deleteTagAction($post_id, $tag_id) {

	$post = $this->findOne($this->_name, $post_id);
	$tag = $this->findOne('Tag', $tag_id);

	$post->getTag()->removeElement($tag);
	$tag->getPost()->removeElement($post);

	$em = $this->getEm();
	$em->persist($post);
	$em->persist($tag);
	$em->flush();
	return $this->redirect($this->generateUrl('_admin_post_show', array('id' => $post_id)));
	
    }
}

