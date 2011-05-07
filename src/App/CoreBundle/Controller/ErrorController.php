<?php

namespace App\CoreBundle\Controller;

class ErrorController extends BaseController {

    public function error403Action() {
        return $this->renderTpl('Error:error403');
    }

}
