<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\Translator;

class MeOrdersType extends AbstractType
{
	private $translator;
	public function __construct(Translator $translator)
	{
		$this->translator = $translator;
	}
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array( 'label' => $this->translator->trans('order_phone')))
            ->add('serviceType', 'choice', array( 'choices' => array( 1 => 'Moex Delivery', 2 => 'Moex Go', 3 => 'Moex Food', 4 => 'Moex Shopping', 5 => 'Moex School'), 'label' =>  $this->translator->trans('oder_servicetype')))
            ->add('orderName', 'text', array( 'label' => $this->translator->trans('order_ordername')))
            ->add('startTime', 'datetime', array('widget' => 'single_text', 'required' => false, 'attr' => array('class' => 'txt-time', 'readonly' => 'readonly') , 'label' => $this->translator->trans('order_starttime')))
            ->add('orderInfo', 'textarea', array('required' => false, 'label' => $this->translator->trans('order_orderinfo')))
            ->add('orderFrom', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_orderfrom')))
            ->add('orderTo', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_orderto')))
            ->add('distance', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_distance')))
            ->add('price', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_price')))
            ->add('lat', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_lat')))
            ->add('lng', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_lng')))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_meorderstype';
    }
}
