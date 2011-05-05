<?php

namespace App\WebBundle\Controller;

class UserController extends WebBaseController {

    protected $_name = 'User';

    public function signupAction() {
        $form = $this->getForm('Signup');

        $user = $this->getEntity($this->_name);
        $form->setData($user);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            $user->setCreatedAt(new \DateTime);
            $user->setUpdatedAt(new \DateTime);
            $user->setTeam($this->findOne('Team', 1));
            if ($form->isValid()) {
                //Encore password
                $user->setPassword($this->getEncodePassword($user));
                
                $em = $this->getEm();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('_home'));
            }
        }

        $form = $form->createView();
        return $this->renderTpl($this->_name . ':signup', compact('form'));
    }

}
