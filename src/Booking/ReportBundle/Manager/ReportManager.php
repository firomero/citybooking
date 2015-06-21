<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 20/06/15
 * Time: 21:36
 */

namespace Booking\ReportBundle\Manager;


use Booking\BookingBundle\Entity\Actividad;
use Booking\BookingBundle\Entity\Casa;
use Booking\BookingBundle\Entity\Reservacion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use General\NomencladorBundle\Entity\Agencia;

class ReportManager {

    protected $em;

    public function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
    }

    /**
     * @param Casa $casa
     * @return array
     */
    public function reporteCasa(Casa $casa){

        $qb = $this->em->createQueryBuilder('r');

        /** @var QueryBuilder $qb */
        $reservaciones = $qb->select('r')
            ->from('BookingBundle:Reservacion','r')
            ->innerJoin('r.casa','casa')
            ->where('casa.id=?',':idcasa')
            ->setParameter('idcasa',$casa->getId())
            ->getQuery()->getResult();

        $dataResult = array();
        /** @var Reservacion $reser */
        foreach ($reservaciones as $reser) {

            $dataResult[]=$reser->toArray();
        }

        return $dataResult;

    }

    /**
     * Devuelve las reservaciones realizadas por una agencia.
     * @param Agencia $agencia
     * @return array
     */
    public function reporteAgencia(Agencia $agencia){
        $qb = $this->em->createQueryBuilder('r');

        /** @var QueryBuilder $qb */
        $reservaciones = $qb->select('r')
            ->from('BookingBundle:Reservacion','r')
            ->innerJoin('r.agencia','agencia')
            ->where('agencia.id=?',':idagencia')
            ->setParameter('idagencia',$agencia->getId())
            ->getQuery()->getResult();

        $dataResult = array();
        /** @var Reservacion $reser */
        foreach ($reservaciones as $reser) {

            $dataResult[]=$reser->toArray();
        }

        return $dataResult;
    }


    /**
     * Devuelve las actividades asociadas a una reservacion.
     * @param Reservacion $reservacion
     * @internal param Reservacion $agencia
     * @return array
     */
    public function invoiceActivity(Reservacion $reservacion){
        $qb = $this->em->createQueryBuilder('a');

        /** @var QueryBuilder $qb */
        $actividades = $qb->select('a')
            ->from('BookingBundle:Actividad','a')
            ->innerJoin('a.reservacion.','reservacion')
            ->where('reservacion.id=?',':idreservacion')
            ->setParameter('idreservacion',$reservacion->getId())
            ->getQuery()->getResult();

        $dataResult = array();
        /** @var Actividad $actividad */
        foreach ($actividades as $actividad) {

            $dataResult[]=$actividad->toArray();
        }

        return $dataResult;
    }


} 