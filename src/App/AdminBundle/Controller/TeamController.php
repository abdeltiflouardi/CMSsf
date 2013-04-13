<?php

namespace App\AdminBundle\Controller;

class TeamController extends AdminBaseController
{

    protected $name = 'Team';

    public function indexAction()
    {
        $teams = $this->paginator($this->_name);

        return $this->renderTpl($this->_name . ':index', compact('teams'));
    }
}
