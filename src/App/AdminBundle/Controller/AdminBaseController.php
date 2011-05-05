<?php

namespace App\AdminBundle\Controller;

use App\CoreBundle\Controller\BaseController;

class AdminBaseController extends BaseController {

    protected $_namespace = 'AppAdminBundle:';
    protected $_name = null;
    
    public function addAction() {
        return $this->addItem($this->_name);
    }

    public function editAction($id) {
        return $this->editItem($this->_name, $id);
    }

    public function deleteAction($id) {
        return $this->removeItem($this->_name, $id);
    }    
 }
