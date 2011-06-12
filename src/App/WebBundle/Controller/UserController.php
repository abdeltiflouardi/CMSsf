<?php

namespace App\WebBundle\Controller;

class UserController extends WebBaseController {

    protected $_name = 'User';

    public function signinAction() {
        $this->menu();
        $this->renderNavigation();
        $this->meta();

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
                //Encore password
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

}
