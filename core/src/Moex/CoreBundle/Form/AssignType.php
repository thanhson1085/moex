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
            ->add('driver_money', 'number', array( 'label' => $this->translator->trans("orderdriver_drivermoney")))
        ;
    }

    public function getName()
    {
        return 'moex_corebundle_assigntype';
    }
}
