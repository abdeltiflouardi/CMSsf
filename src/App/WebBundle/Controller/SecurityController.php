<?php

namespace App\WebBundle\Controller;

class SecurityController extends WebBaseController
{
    public function indexAction()
    {
        return $this->renderTpl('Default:login');
    }
}
