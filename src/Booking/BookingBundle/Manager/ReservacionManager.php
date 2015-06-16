<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 15/06/15
 * Time: 23:26
 */

namespace Booking\BookingBundle\Manager;


use Booking\BookingBundle\Entity\Casa;
use Booking\BookingBundle\Entity\Reservacion;
use Doctrine\ORM\EntityManager;
use General\NomencladorBundle\Entity\TipoHab;

class ReservacionManager {

    protected $_em;

    /**
     * @param EntityManager $entityManager
     */
    public function __constructor(EntityManager $entityManager)
    {

        $this->_em = $entityManager;
    }

    protected $map = array(
        'single-room'=>array('unit'=>25, 'breakfast'=>4),
        'double-room'=>array('unit'=>25, 'breakfast'=>8),
        'twin-room'=>array('unit'=>25, 'breakfast'=>8),
        'triple-room'=>array('unit'=>30, 'breakfast'=>12),
    );

    /**
     * @param Reservacion $entity
     */
    public function setPrecio(Reservacion $entity){
        $total = 0;
        $habitaciones = $entity->getTipoHab();

        foreach ($habitaciones as $habitacion) {
            /**
             * @var TipoHab $habitacion
             * */
            $total+=$this->map[$habitacion->getNombre()]['unit']*$entity->getNoches()+$this->map[$habitacion->getNombre()]['breakfast'];
        }

        $entity->setPrecio($total);

    }

    public function getCasasDisponibles(\DateTime $checkin, \DateTime $checkout, array $habitaciones){
        /**
         *@var EntityManager $em
         */
        $em = $this->_em;
        $reservadas = $em->createQueryBuilder('r.casa')
            ->select('r.casa')
            ->from('BookingBundle:Reservacion','r')
            ->innerJoin('r.casa','casa')
            ->where('r.checkin >= :checkin')
            ->orWhere('r.checkout<= :checkout')
            ->setParameters(array(
                'checkout'=>$checkout,
                'checkin'=>$checkin
            ))
            ->getQuery()
            ->getResult();
        $reservadasId = array();
        foreach ($reservadas as $reservada) {
            /**
            * @var Casa $reservada
             */
            $reservadasId[]=$reservada->getId();
        }

        $qb = $em->createQueryBuilder('c');
        $casas = $qb->select('c')
            ->from('BookingBundle:Casa','c')
            ->where($qb->expr()->notIn('c.id','ids'))
            ->setParameter('ids',$reservadasId)
            ->getQuery()
            ->getResult();
    }

    protected function filterDate($casas, $habitaciones){
        //TODO aqui es comprobar que la casa cumpla los requrimientos de las habitaciones


    }

} 