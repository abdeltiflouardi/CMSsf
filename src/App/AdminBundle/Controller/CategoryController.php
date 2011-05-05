<?php

namespace App\AdminBundle\Controller;

class CategoryController extends AdminBaseController {

    protected $_name = 'Category';
    
    public function indexAction() {
        $categories = $this->paginator($this->_name);
        return $this->renderTpl($this->_name . ':index', compact('categories'));
    }    

}

