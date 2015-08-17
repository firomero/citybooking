<?php

namespace Booking\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //todo revisar el data transformer del nombre
        $builder
            ->add('nombre', 'text', array('attr'=>array('class'=>'cliente-name  form-control')))
            ->add('referencia', 'text', array('attr'=>array('class'=>'referencia-name form-control')))
//            ->add('actividades')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Booking\BookingBundle\Entity\Cliente'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'booking_bookingbundle_cliente';
    }
}
