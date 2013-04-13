<?php

namespace App\AdminBundle\Controller;

class CommentController extends AdminBaseController
{

    protected $name = 'Comment';

    public function indexAction()
    {
        $comments = $this->paginator($this->name);

        return $this->renderTpl($this->name . ':index', compact('comments'));
    }
}
