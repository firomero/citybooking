<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 16/06/15
 * Time: 8:03
 */

namespace Booking\BookingBundle\Form;

use Booking\BookingBundle\DataTransformers\ClienteTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClienteSelectorType extends AbstractType
{
    protected $em;

    public function __construct(EntityManager $om)
    {
        $this->em = $om;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ClienteTransformer($this->em);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'clienteselector_type';
    }

    public function getParent()
    {
        return 'text';
    }
}
