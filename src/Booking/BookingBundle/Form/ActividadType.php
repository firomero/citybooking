<?php

namespace Booking\BookingBundle\Form;

use Booking\BookingBundle\DataTransformers\DateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActividadType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($builder->create('fecha','text')->addViewTransformer(new DateTransformer()))
            ->add('guia')
            ->add('total')
            ->add('pax')
            ->add('precioguia')
            ->add('tipoActividad')
            ->add('reservacion')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\BookingBundle\Entity\Actividad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'booking_bookingbundle_actividad';
    }
}
