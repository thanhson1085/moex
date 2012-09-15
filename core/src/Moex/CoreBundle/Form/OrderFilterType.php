<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\Translator;

class OrderFilterType extends AbstractType
{
	private $translator;
	public function __construct(Translator $translator){
		$this->translator = $translator;
	}
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array('required' => false, 'label' => $this->translator->trans('order_phone')))
            ->add('orderName', 'text', array('required' => false, 'label' => $this->translator->trans('order_ordername')))
            ->add('orderInfo', 'text', array('required' => false, 'label' => $this->translator->trans('order_orderinfo')))
            ->add('orderFrom', 'text', array('required' => false, 'label' => $this->translator->trans('order_orderfrom')))
            ->add('orderTo', 'text', array('required' => false, 'label' => $this->translator->trans('order_orderto')))
            ->add('price', 'text', array('required' => false, 'label' => $this->translator->trans('order_price')))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_orderfiltertype';
    }
}
