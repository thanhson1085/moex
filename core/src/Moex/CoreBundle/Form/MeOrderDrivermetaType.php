<?php
 
namespace Moex\CoreBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
 
class MeOrderDrivermetaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('metaKey','hidden', array('attr' => array('class' => 'order-metakey-position')))
            ->add('metaValue', 'text', array('required' => false, 'attr' => array('class' => 'order-position')))
        ;
    }
 
    public function getName()
    {
        return 'moex_corebundle_orderdrivermetatype';
    }
    
    public function getDefaultOptions(array $options){
        return array('data_class' => 'Moex\CoreBundle\Entity\MeOrderDrivermeta');
    }
}
