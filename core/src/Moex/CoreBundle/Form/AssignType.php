<?php
namespace Moex\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\Translator;

class AssignType extends AbstractType
{
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('driverMoney', 'number', array( 'label' => $this->translator->trans("orderdriver_drivermoney")))
            ->add('roadMoney', 'number', array( 'label' => $this->translator->trans("order_roadprice")))
            ->add('money', 'number', array( 'label' => $this->translator->trans("orderdriver_money")))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_assigntype';
    }
}
