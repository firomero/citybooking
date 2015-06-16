<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 16/06/15
 * Time: 7:51
 */

namespace Booking\BookingBundle\Form;


use Booking\BookingBundle\DataTransformers\AgenciaTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgenciaSelectorType extends AbstractType{

    protected $em;

    public function __construct(EntityManager $om)
    {
        $this->em = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new AgenciaTransformer($this->em);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }

    public function getParent(){
        return 'text';
    }
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "agenciaselector_type";
    }
}