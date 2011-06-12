<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class User extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('team', null, array('property' => 'name'));
        $builder->add('username');
        $builder->add('password', 'repeated', 
                array('type' => 'password', 'first_name' => 'Password', 'second_name' => 'Again'));        
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'App\CoreBundle\Entity\User',
        );
    }

    public function getName()
    {
         return 'user';
    }
}
