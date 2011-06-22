<?php

namespace App\WebBundle\Controller;

class UserController extends WebBaseController {

    protected $_name = 'User';

    public function signinAction() {
        $form_signin = $this->getForm('Signin')->createView();
        $form_signup = $this->getForm('Signup')->createView(); 
 
        $this->renderData(compact('form_signin', 'form_signup'));
        return $this->renderTpl('User:signin');
    }

    public function signupAction() {
        $form = $this->getForm('Signup');

        $user = $this->getEntity($this->_name);
        $form->setData($user);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            $team = $this->findOne('Team', 3);
            $user->addTeam($team);
            $team->addUser($user);

            if ($form->isValid()) {
                //Encode password
                $user->setPassword($this->getEncodePassword($user));
                
                $em = $this->getEm();
                $em->persist($team);
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('_home'));
            }
        }

        $form = $form->createView();
        return $this->renderTpl($this->_name . ':signup', compact('form'));
    }

    public function profileAction() {
        $user = $this->getUser();

	$form = $this->getForm('Signup', $user);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
             $form->bindRequest($request);
             if ($form->isValid()) {

                  $user->setPassword($this->getEncodePassword($user));

                  $em = $this->getEm();
                  $em->persist($user);              
                  $em->flush();

                  $this->flash('Profile modifié');
                  return $this->redirect($this->generateUrl('_profile'));
             }
        }
        $form = $form->createView();
	
        return $this->renderTpl($this->_name . ':profile', compact('form'));
    }
    public function commentsAction() {
        $comments = $this->getUser()->getComments();

        return $this->renderTpl($this->_name . ':comments', compact('comments'));
    }

    public function commentEditAction($comment_id) {
        $comment = $this->findOne('Comment', $comment_id);

        if (!$comment)
            return $this->notFound('Commentaire non trouvé', false);

        if (!$this->get('security.context')->isGranted('EDIT', $comment) &&
            !$this->get('security.context')->isGranted('ROLE_MODERATE'))
            return $this->accessDenied(null, false);        

        $form = $this->getForm('Comment', $comment);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                 $em = $this->getEm();
                 $em->persist($comment);
                 $em->flush();

                 $this->flash('Commentaire modifié');
                 return $this->myRedirect('_comments');
            }
        }

        $form = $form->createView();

        return $this->renderTpl($this->_name . ':comment_edit', compact('form'));
    }

    public function commentDeleteAction($comment_id) {

        $comment = $this->findOne('Comment', $comment_id);       

        if (!$comment)
            return $this->notFound(sprintf('Comment #%s non trouvé', $comment_id), false);

        if (!$this->get('security.context')->isGranted('DELETE', $comment) && 
            !$this->get('security.context')->isGranted('ROLE_MODERATE'))
            return $this->accessDenied(null, false);

        if ($confirm = $this->get('request')->query->get('confirm')) {
            $em = $this->getEm();
            $em->remove($comment);
            $em->flush();

            $this->flash('Commentaire supprimé');
            return $this->myRedirect('_comments');
        }

        return $this->renderTpl($this->_name . ':comment_delete', compact('comment'));
    }

    public function postsAction() {
        $posts = $this->getUser()->getPosts();

        return $this->renderTpl($this->_name . ':posts', compact('posts'));
    }

    public function postEditAction($post_id) {
        // @TODO ACL just owner & moderator can edit
        $post = $this->findOne('Post', $post_id);

        if (!$post)
            return $this->notFound('Article non trouvé', false);

        $form = $this->getForm('Post', $post);
        $form->remove('user');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                 $em = $this->getEm();
                 $em->persist($post);
                 $em->flush();
                 $this->get('tags')->editTags($post_id);

                 $this->flash('Article modifié');
                 return $this->myRedirect('_posts');
            }
        }

        $form = $form->createView();

        return $this->renderTpl($this->_name . ':post_edit', compact('form'));
    }

    public function postDeleteAction($post_id) {
        // @TODO Acl juste owner & admin can delete article
        $post = $this->findOne('Post', $post_id);       
        if ($confirm = $this->get('request')->query->get('confirm')) {

            $em = $this->getEm();
            $em->remove($post);
            $em->flush();

            $this->flash('Article supprimé');
            return $this->myRedirect('_posts');
        }

        return $this->renderTpl($this->_name . ':post_delete', compact('post'));

    }

}
