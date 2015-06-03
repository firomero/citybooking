<?php

namespace Booking\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CasaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('direccion')
            ->add('nombre')
            ->add('telefono')
            ->add('categoria')
            ->add('cantidadHab')
            ->add('clima')
            ->add('banno')
            ->add('disponible')
            ->add('observacion')
            ->add('propietario')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\BookingBundle\Entity\Casa'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'booking_bookingbundle_casa';
    }
}
