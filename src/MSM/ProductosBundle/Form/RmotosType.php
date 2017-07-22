<?php

namespace MSM\ProductosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RmotosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('producto', 'text', array('label' => 'Producto'))
            ->add('modeloTipo', 'text')
            ->add('cantidad', 'integer')
            ->add('costo', 'number')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MSM\ProductosBundle\Entity\Rmotos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'msm_productosbundle_rmotos';
    }
}
