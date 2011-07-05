<?php

namespace App\CoreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class Comment extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('comment');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'required' => false,
            'data_class' => 'App\CoreBundle\Entity\Comment',
        );
    }
}
