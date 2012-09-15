<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\Translator;

class MeDriversType extends AbstractType
{
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('driverName', 'text', array( 'label' => $this->translator->trans("driver_drivername")))
            ->add('driverAge', 'text', array( 'label' => $this->translator->trans("driver_driverage")))
            ->add('driverInfo', 'textarea', array('required' => false, 'label' => $this->translator->trans("driver_driverinfo")))
            ->add('phone', 'text', array('label' => $this->translator->trans('driver_phone')))
            ->add('position', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans("driver_position")))
            ->add('lat', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans("driver_lat")))
            ->add('lng', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans("driver_lng")))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_medriverstype';
    }
}
