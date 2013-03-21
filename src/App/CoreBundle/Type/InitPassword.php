<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilder;

class InitPassword extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('oldPassword', 'password', array('label' => 'Old password'));
        $builder->add('newPassword', 'repeated', array('type' => 'password', 'first_name' => 'New password', 'second_name' => 'Confirm'));
    }

    public function getDefaultOptions(array $options)
    {
        return array('required' => false);
    }

    public function getName()
    {
        return 'initpassword';
    }
}
