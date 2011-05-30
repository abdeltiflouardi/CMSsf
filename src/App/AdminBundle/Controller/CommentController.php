<?php

namespace App\AdminBundle\Controller;

class CommentController extends AdminBaseController {

    protected $_name = 'Comment';

    public function indexAction() {
        $comments = $this->paginator($this->_name);
        return $this->renderTpl($this->_name . ':index', compact('comments'));
    }

}

