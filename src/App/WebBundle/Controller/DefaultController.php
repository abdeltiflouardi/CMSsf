<?php

namespace App\WebBundle\Controller;

class DefaultController extends WebBaseController
{
    public function indexAction()
    {
        $posts = $this->paginator('Post');
        return $this->renderTpl('Default:index', compact('posts'));
    }
}
