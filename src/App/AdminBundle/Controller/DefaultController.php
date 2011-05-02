<?php

namespace App\AdminBundle\Controller;

class DefaultController extends AdminBaseController
{
    public function indexAction()
    {
        return $this->renderTpl('Default:index');
    }
}
