<?php

namespace Booking\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropietarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('attr'=>array('class'=>'form-control')))
            ->add('ci','text',array('attr'=>array('class'=>'form-control')))
            ->add('cell','text',array('attr'=>array('class'=>'form-control')));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Booking\BookingBundle\Entity\Propietario'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'booking_bookingbundle_propietario';
    }
}
