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
	return $this->renderTpl($this->_name . ':comment_edit');
    }

    public function commentDeleteAction($comment_id) {
	return $this->renderTpl($this->_name . 'comment_delete');
    }

    public function postsAction() {
        $posts = $this->getUser()->getPosts();

        return $this->renderTpl($this->_name . ':posts', compact('posts'));
    }

}
