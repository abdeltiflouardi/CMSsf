<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilder;

class TagList extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('tag', 'entity', array('class'    => 'App\CoreBundle\Entity\Tag', 'property' => 'name'));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'App\CoreBundle\Entity\Tag',
        );
    }

    public function getName()
    {
        return 'tags';
    }
}
