<?php

namespace App\AdminBundle\Controller;

use App\CoreBundle\Controller\BaseController;

class AdminBaseController extends BaseController
{

    protected $namespace = 'AppAdminBundle:';
    protected $name      = null;

    public function addAction()
    {
        return $this->addItem($this->name);
    }

    public function editAction($id)
    {
        return $this->editItem($this->name, $id);
    }

    public function deleteAction($id)
    {
        return $this->removeItem($this->name, $id);
    }
}
