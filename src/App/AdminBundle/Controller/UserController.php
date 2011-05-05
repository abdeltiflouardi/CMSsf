<?php

namespace App\AdminBundle\Controller;

class UserController extends AdminBaseController {

    protected $_name = 'User';

    public function indexAction() {
        $users = $this->paginator($this->_name);
        return $this->renderTpl($this->_name . ':index', compact('users'));
    }

    public function editAction($id) {
        /**
         * Get posted password
         */
        $request = $this->get('request');
        $request_user = $request->request->get('user');
        $password = $request_user['password']['Password'];

        /**
         * Return User object
         */
        $user = $this->findOne($this->_name, $id);
        $user->setPassword($password);

        /**
         * Return User encode password
         */
        $password = $this->getEncodePassword($user);
        return $this->editItem($this->_name, $id, array('afterValid' => array('setPassword' => $password)));
    }

    public function addAction() {
        /**
         * Get posted username and password
         */
        $request = $this->get('request');
        $request_user = $request->request->get('user');
        $username = $request_user['username'];
        $password = $request_user['password']['Password'];

        /**
         * Return User object
         */
        $user = $this->getEntity($this->_name);
        $user->setUsername($username);
        $user->setPassword($password);

        /**
         * Return User encode password
         */
        $password = $this->getEncodePassword($user);
        return $this->addItem($this->_name, array('afterValid' => array('setPassword' => $password)));
    }    
}

