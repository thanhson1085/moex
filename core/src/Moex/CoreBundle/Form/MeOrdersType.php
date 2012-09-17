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
            ->add('orderName', 'text', array( 'label' => $this->translator->trans('order_ordername')))
            ->add('orderInfo', 'textarea', array('required' => false, 'label' => $this->translator->trans('order_orderinfo')))
            ->add('orderFrom', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_orderfrom')))
            ->add('orderTo', 'text', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_orderto')))
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
