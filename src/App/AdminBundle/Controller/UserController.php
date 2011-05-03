<?php

namespace App\AdminBundle\Controller;

class UserController extends AdminBaseController {

    protected $_name = 'User';
    
    public function indexAction() {
        $users = $this->getAll($this->_name);
        return $this->renderTpl($this->_name . ':index', compact('users'));
    }    

}

