<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MeOrdersType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('phone')
            ->add('orderInfo')
            ->add('orderFrom')
            ->add('orderTo')
            ->add('price')
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_meorderstype';
    }
}
