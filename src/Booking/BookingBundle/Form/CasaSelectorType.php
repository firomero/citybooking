<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 10/07/15
 * Time: 22:37
 */

namespace Booking\BookingBundle\Form;


use Booking\BookingBundle\DataTransformers\CasaTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CasaSelectorType extends AbstractType{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'casaselector_type';
    }

    public function getParent(){
        return 'text';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }

    protected $em;

    public function __construct(EntityManager $om)
    {
        $this->em = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new CasaTransformer($this->em);
        $builder->addModelTransformer($transformer);
    }
}