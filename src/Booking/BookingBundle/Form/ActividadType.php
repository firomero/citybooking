<?php

namespace Booking\BookingBundle\Form;

use Booking\BookingBundle\DataTransformers\DateTransformer;
use Booking\BookingBundle\DataTransformers\TimeTransformer;
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
            ->add($builder->create('fecha', 'text')->addViewTransformer(new DateTransformer()))
            ->add($builder->create('hora', 'text')->addViewTransformer(new TimeTransformer()))
            ->add('lugar')
            ->add('coordinacion')
            ->add('pax')
            ->add('precio')
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
