<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\Translator;

class DriverFilterType extends AbstractType
{
	private $translator;
	public function __construct(Translator $translator){
		$this->translator = $translator;
	}
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array('required' => false, 'label' => $this->translator->trans('driver_phone')))
            ->add('driverName', 'text', array('required' => false, 'label' => $this->translator->trans('driver_drivername')))
            ->add('driverInfo', 'text', array('required' => false, 'label' => $this->translator->trans('driver_driverinfo')))
            ->add('position', 'text', array('required' => false, 'label' => $this->translator->trans('driver_position')))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_driverfiltertype';
    }
}
