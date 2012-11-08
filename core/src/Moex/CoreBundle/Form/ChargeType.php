<?php
namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\Translator;

class ChargeType extends AbstractType
{
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('amount', 'number', array( 'label' => $this->translator->trans("money_amount")))
            ->add('description', 'textarea', array('required' => false, 'label' => $this->translator->trans("money_description")))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_chargetype';
    }
}
