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

    /**
     * Returns all invoice from activities
     * filters casa | all
     * @return array
     */
    public function invoiceTour($casa=array()){
        $em = $this->em;

        $reservaciones = $em->getRepository('BookingBundle:Reservacion')->findBy($casa);

        $dataOutput = array();

        foreach ($reservaciones as $book) {
            $dataOutput[] = array(
                'date'=> date_format(new \DateTime(),'d/m/Y'),
                'supplier_agency'=> $book->getAgencia()->getNombre(),
                'supplier_name'=> $book->getCasa()->getPropietario()->getNombre(),
                'supplier_mobile'=> $book->getCasa()->getTelefono(),
                'supplier_phone'=> '0053 4199 6686',
                'supplier_email'=> 'bookingcuba.micubalocal@gmail.com',
                'client_agency'=> 'MiCuba - Reisspecialist in    Cuba&nbsp;',
                'client_address'=> 'Veembroederhof 173, 1019 HD in Amsterdam, Nl',
                'client_mobile'=> '0031 6 442135578',
                'client_web'=> 'www.micuba.nl',
                'client_email'=> 'julio@micuba.nl',
                'booking_name'=>$book->getCliente()->getNombre(),
                'booking_pax'=>$book->getPax(),
                'booking_number'=>$book->getCliente()->getReferencia(),
                'total' => $book->getPrecio(),
                'services'=>call_user_func(function()use($book){
                    $services = array();
                    $activities = $book->getActividades();

                    foreach ($activities as $activity) {
                        /**
                         * @var Actividad $activity
                         */
                        $services[]=array(
                            'services_checkIn' => $activity->getFecha()->format('d/m/Y'),
                            'services_checkOut' => $activity->getFecha()->format('d/m/Y'),
                            'services_description' => $activity->getTipoActividad()->getNombre(),
                            'services_price' => $activity->getPrecioguia(),
                            'services_total' => $activity->getTotal()
                        );
                    }
                    return $services;
                })
            );
        }

        return $dataOutput;
    }

    /**
     * Generates the invoice for all bookings
     * Filters casa | agencia | all
     * @return array
     */
    public function invoiceBooking($filters=array()){
        $data = array(
            'list'=>array()
        );
        $em = $this->em;

        $reservaciones = $em->getRepository('BookingBundle:Reservacion')->findBy($filters);

        foreach ($reservaciones as $book) {
            $data['list'][]=array(

                'referencia' => $book->getCliente()->getReferencia(),
                'cliente'=>$book->getCliente()->getNombre(),
                'entrada'=>$book->getCheckin()->format('d/m/Y'),
                'salida'=>$book->getCheckout()->format('d/m/Y'),
                'n'=>$book->getNoches(),
                'servicio'=>$book->getCasa()->getNombre(),
                'p'=>$book->getPax(),
                'hab'=> implode(',',$book->roomList()),
                'fact'=>$book->getPrecio(),
                'pagar'=>$book->getPrecio(),
                'com'=>5*count($book->getCasa()->getCantidadHab())*$book->getNoches(),
                'observaciones'=>$book->getObservacion().'\n'.implode(',', $book->activityList())
            );
        }

        return $data;

    }


} 