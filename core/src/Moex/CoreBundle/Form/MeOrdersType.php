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
		$ordermeta = new MeOrdermetaType();
        $builder
            ->add('phone', 'text', array( 'label' => $this->translator->trans('order_phone')))
            ->add('customerId', 'text', array( 'label' => $this->translator->trans('order_customerid'), 'attr' => array('readonly' => 'readonly')))
            ->add('serviceType', 'choice', array( 'choices' => array( 1 => 'Moex Delivery', 2 => 'Moex Go', 3 => 'Moex Food', 4 => 'Moex Shopping', 5 => 'Moex School', 6 => 'Moex Others'), 'label' =>  $this->translator->trans('order_servicetype')))
            ->add('orderCode', 'text', array( 'label' => $this->translator->trans('order_ordercode'), 'required' => false ))
            ->add('orderName', 'text', array( 'label' => $this->translator->trans('order_ordername')))
            ->add('startTime', 'datetime', array('widget' => 'single_text', 'required' => false, 'attr' => array('class' => 'txt-time', 'readonly' => 'readonly') , 'label' => $this->translator->trans('order_starttime')))
            ->add('orderTime', 'datetime', array('widget' => 'single_text', 'required' => true, 'attr' => array('class' => 'txt-time', 'readonly' => 'readonly') , 'label' => $this->translator->trans('order_ordertime')))
            ->add('orderInfo', 'textarea', array('required' => false, 'label' => $this->translator->trans('order_orderinfo')))
            ->add('orderFrom', 'text', array( 'required' => true, 'label' => $this->translator->trans('order_orderfrom')))
            ->add('orderTo', 'text', array( 'required' => true, 'label' => $this->translator->trans('order_orderto')))
            ->add('thereturn', 'checkbox', array( 'required' => false, 'label' => $this->translator->trans('order_thereturn')))
            ->add('receiverName', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_receivername')))
            ->add('receiverPhone', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_receiverphone')))
            ->add('receiverAddress', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_receiveraddress')))
            ->add('senderAddress', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_senderaddress')))
            ->add('distance', 'text', array( 'attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_distance')))
            ->add('surcharge', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_surcharge')))
            ->add('promotion', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_promotion')))
            ->add('price', 'text', array( 'label' => $this->translator->trans('order_price')))
            ->add('extraPrice', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_extraprice')))
            ->add('totalPrice', 'text', array( 'required' => false, 'label' => $this->translator->trans('order_totalprice')))
            ->add('roadPrice', 'text', array( 'label' => $this->translator->trans('order_roadprice')))
            ->add('lat', 'hidden', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_lat')))
            ->add('lng', 'hidden', array('attr' => array('readonly' => 'readonly'), 'label' => $this->translator->trans('order_lng')))
			->add('ordermeta', 'collection', array(
				'label' => $this->translator->trans('order_position'),
				'type' => $ordermeta, 
				'allow_add' => true,
				'prototype' => true,
				'by_reference' => false,
				))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_meorderstype';
    }

    public function getDefaultOptions(array $options){
        return array('data_class' => 'Moex\CoreBundle\Entity\MeOrders');
    }
}
