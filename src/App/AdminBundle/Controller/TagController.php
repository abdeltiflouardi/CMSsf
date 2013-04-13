<?php

namespace App\AdminBundle\Controller;

class TagController extends AdminBaseController
{

    protected $name = 'Tag';

    public function indexAction()
    {
        $tags = $this->paginator($this->name);

        return $this->renderTpl($this->name . ':index', compact('tags'));
    }
}
