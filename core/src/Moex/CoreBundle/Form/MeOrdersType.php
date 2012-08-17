<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MeOrdersType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('userId')
            ->add('orderFrom')
            ->add('orderTo')
            ->add('orderInfo')
            ->add('phone')
            ->add('price')
            //->add('createdAt')
            //->add('updatetedAt')
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_meorderstype';
    }
}
