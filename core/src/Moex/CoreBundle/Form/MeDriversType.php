<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MeDriversType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('driverName')
            ->add('driverAge')
            ->add('driverInfo', 'textarea', array('required' => false))
            ->add('phone')
            ->add('position', 'text', array('attr' => array('readonly' => 'readonly')))
            ->add('lat', 'text', array('attr' => array('readonly' => 'readonly')))
            ->add('lng', 'text', array('attr' => array('readonly' => 'readonly')))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_medriverstype';
    }
}
