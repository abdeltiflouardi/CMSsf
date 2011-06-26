<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InitPassword extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('oldPassword', 'password');
        $builder->add('newPassword', 'repeated', array('type' => 'password'));
    }

    public function getDefaultOptions(array $options)
    {
        return array('required' => false);
    }
}
