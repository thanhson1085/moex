<?php

namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\Translator;

class UserFilterType extends AbstractType
{
	private $translator;
	public function __construct(Translator $translator){
		$this->translator = $translator;
	}
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('userLogin', 'text', array('required' => false, 'label' => $this->translator->trans('user_userlogin')))
            ->add('userEmail', 'text', array('required' => false, 'label' => $this->translator->trans('user_useremail')))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_userfiltertype';
    }
}
