<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class Post extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('category', null, array('class' => 'App\CoreBundle\Entity\Category', 'property' => 'name'));
        $builder->add('title');
        $builder->add('body');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'App\CoreBundle\Entity\Post',
        );
    }
}
