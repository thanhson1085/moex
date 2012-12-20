<?php
 
namespace Moex\CoreBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
 
class TheReturnType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('metaKey','hidden', array( 'data' => 'ORDER_THERETUN', 'attr' => array('class' => '')))
            ->add('metaValue', 'checkbox', array( 'required' => false, 'attr' => array('class' => '')))
        ;
    }
 
    public function getName()
    {
        return 'moex_corebundle_thereturntype';
    }
    
    public function getDefaultOptions(array $options){
        return array('data_class' => 'Moex\CoreBundle\Entity\MeOrdermeta');
    }
}
