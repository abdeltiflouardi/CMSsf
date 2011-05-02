<?php

namespace App\AdminBundle\Controller;

class TagController extends AdminBaseController {

    protected $_name = 'Tag';
    
    public function indexAction() {
        $tags = $this->getAll($this->_name);
        return $this->renderTpl($this->_name . ':index', compact('tags'));
    }    

}

