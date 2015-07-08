<?php

namespace Booking\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReservacionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('checkin','text')
            ->add('checkout','text')
            ->add('noches')
            ->add('pax')
            ->add('tipoHab')
            ->add('precio')
            ->add('confirmado','text',array('attr'=>array('class'=>'form-control dating')))
            ->add('observacion')
            ->add('casa')
            ->add('agencia','agenciaselector_type')
            ->add('cliente','clienteselector_type')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\BookingBundle\Entity\Reservacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'booking_bookingbundle_reservacion';
    }
}
