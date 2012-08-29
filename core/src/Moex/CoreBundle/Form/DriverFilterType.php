<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DriverFilterType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array('required' => false))
            ->add('driverName', 'text', array('required' => false))
            ->add('driverInfo', 'text', array('required' => false))
            ->add('position', 'text', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_driverfiltertype';
    }
}
