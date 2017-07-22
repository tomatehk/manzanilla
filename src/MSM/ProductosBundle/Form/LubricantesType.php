<?php

namespace MSM\ProductosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LubricantesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aceite', 'text', array('label' => 'Aceite'))
            ->add('tipo', 'text')
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
            'data_class' => 'MSM\ProductosBundle\Entity\Lubricantes'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'msm_productosbundle_lubricantes';
    }
}
