<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends AdminBaseController
{

    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->renderTpl(
            'Security:login',
            array(
                // last username entered by the user
                'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
}
