<?php

namespace Booking\BookingBundle\Form;

use Booking\BookingBundle\DataTransformers\DateTransformer;
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
            ->add($builder->create('checkin','text')->addViewTransformer(new DateTransformer()))
            ->add($builder->create('checkout','text')->addViewTransformer(new DateTransformer()))
            ->add('noches')
            ->add('pax')
            ->add('tipoHab')
            ->add('precio')
            ->add('confirmado','text',array('attr'=>array('class'=>'form-control dating')))
            ->add('observacion','textarea',array('attr'=>array('class'=>'form-control dating')))
            ->add('casa')
            ->add('agencia','agenciaselector_type', array('attr'=>array('class'=>'form-control dating')))
            ->add('cliente','clienteselector_type',array('attr'=>array('class'=>'form-control dating')))
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
