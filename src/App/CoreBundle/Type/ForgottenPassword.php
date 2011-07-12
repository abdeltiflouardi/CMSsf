<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ForgottenPassword extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email');
    }

    public function getDefaultOptions(array $options)
    {
        return array('required' => false);
    }

    public function getName()
    {
        return 'forgottenpassword';
    }
}
