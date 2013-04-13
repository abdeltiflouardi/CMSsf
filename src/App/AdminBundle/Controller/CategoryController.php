<?php

namespace App\AdminBundle\Controller;

class CategoryController extends AdminBaseController
{

    protected $name = 'Category';

    public function indexAction()
    {
        $categories = $this->paginator($this->name);

        return $this->renderTpl($this->name . ':index', compact('categories'));
    }
}
