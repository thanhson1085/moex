<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrderFilterType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array('required' => false))
            ->add('orderName', 'text', array('required' => false))
            ->add('orderInfo', 'text', array('required' => false))
            ->add('orderFrom', 'text', array('required' => false))
            ->add('orderTo', 'text', array('required' => false))
            ->add('price', 'text', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_orderfiltertype';
    }
}
