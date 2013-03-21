<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilder;

class Signin extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('login', 'text');
        $builder->add('password', 'password');     
    }

    public function getDefaultOptions(array $options)
    {
        return array('required' => false);
    }

    public function getName() {
        return 'signin';
    }
}
