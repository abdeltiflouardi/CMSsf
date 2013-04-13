<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilder;

class Category extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('parent', null, array('property' => 'name', 'required' => false));
        $builder->add('name');
        $builder->add('position', 'choice', array('choices' => range(0, 99)));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'App\CoreBundle\Entity\Category',
        );
    }

    public function getName()
    {
        return 'category';
    }
}
