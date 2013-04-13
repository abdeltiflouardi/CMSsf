<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilder;

class Post extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('user', null, array('property' => 'username'));
        $builder->add('category', null, array('property' => 'name'));
        $builder->add('title');
        $builder->add('body');
        $builder->add('words', 'text');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'App\CoreBundle\Entity\Post',
        );
    }

    public function getName()
    {
        return 'post';
    }
}
