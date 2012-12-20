<?php
 
namespace Moex\CoreBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
 
class MeOrdermetaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('metaKey','hidden', array('attr' => array('class' => 'order-metakey-position')))
            ->add('metaValue', 'text', array('attr' => array('class' => 'order-position')))
        ;
    }
 
    public function getName()
    {
        return 'moex_corebundle_ordermetatype';
    }
    
    public function getDefaultOptions(array $options){
        return array('data_class' => 'Moex\CoreBundle\Entity\MeOrdermeta');
    }
}
