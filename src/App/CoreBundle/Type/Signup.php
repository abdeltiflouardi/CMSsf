<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class Signup extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email');
        $builder->add('username');
        $builder->add('password', 'repeated',
                array('type' => 'password', 'first_name' => 'Password', 'second_name' => 'Again'));
    }

    public function getDefaultOptions(array $options)
    {
        return array('required' => false);
    }

    public function getName() {
        return 'signup';
    }
}
