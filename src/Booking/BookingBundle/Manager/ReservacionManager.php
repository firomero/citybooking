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

class ReservacionManager
{

    protected $_em;

    /**
     * @param EntityManager $entityManager
     */
    public function __constructor(EntityManager $entityManager)
    {

        $this->_em = $entityManager;
    }

    protected $map = array(
        'single-room' => array('unit' => 25, 'breakfast' => 4),
        'double-room' => array('unit' => 25, 'breakfast' => 8),
        'twin-room' => array('unit' => 25, 'breakfast' => 8),
        'triple-room' => array('unit' => 30, 'breakfast' => 12),
    );

    /**
     * @param Reservacion $entity
     */
    public function setPrecio(Reservacion $entity)
    {
        $total = 0;
        $habitaciones = $entity->getTipoHab();

        foreach ($habitaciones as $habitacion) {
            /**
             * @var TipoHab $habitacion
             * */
            $total += $this->map[$habitacion->getNombre()]['unit'] * $entity->getNoches(
                ) + $this->map[$habitacion->getNombre()]['breakfast'];
        }

        $entity->setPrecio($total);

    }

    public function getCasasDisponibles(\DateTime $checkin, \DateTime $checkout, array $habitaciones)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->_em;
        $reservadas = $em->createQueryBuilder('r.casa')
            ->select('r.casa')
            ->from('BookingBundle:Reservacion', 'r')
            ->innerJoin('r.casa', 'casa')
            ->where('r.checkin >= :checkin')
            ->orWhere('r.checkout<= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin
                )
            )
            ->getQuery()
            ->getResult();
        $reservadasId = array();
        foreach ($reservadas as $reservada) {
            /**
             * @var Casa $reservada
             */
            $reservadasId[] = $reservada->getId();
        }

        $qb = $em->createQueryBuilder('c');
        $casas = $qb->select('c')
            ->from('BookingBundle:Casa', 'c')
            ->where($qb->expr()->notIn('c.id', 'ids'))
            ->setParameter('ids', $reservadasId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $casas
     * @param $habitaciones
     * @return array
     */
    protected function filtrarCasa($casas, $habitaciones)
    {
        {
            /**
             * @var EntityManager $em
             */
            $em = $this->_em;
            $casasOutput = array();

            /**
             * Ordeno las habitaciones solicitadas por el tipo, de mayor a menor teniendo en cuenta el Peso.
             */
            $this->bubbleSortByTipoHab($habitaciones);

            /*
             * Por cada casa pasada por parámetro, guardo sus habitaciones en un arreglo del que pueda ir eliminando
             * a medida que encuentre un match entre lo que tengo y lo que piden. Este análisis se ejecuta solo si la
             * cantidad de habitaciones de la casa es igual o mayor que la cantidad solicitada.
             * */
            foreach ($casas as $casa) {
                $casaHabs = $em->getRepository('BookingBundle:Habitacion')->findByCasa($casa);
                $checkArray = array();
                foreach ($casaHabs->getTipo() as $casaHab) {
                    $checkArray[] = $casaHab;
                }
                $this->bubbleSortByTipoHab($checkArray);
                if ($casa->getCantidadHab() >= count($habitaciones)) {
                    foreach ($habitaciones as $habitacion) {
                        foreach ($checkArray as $index => $value) {
                            if ($value->getPeso() >= $habitacion->getPeso()) {
                                unset($checkArray[$index]);
                            }
                        }
                    }
                    if (count($checkArray) == $casa->getCantidadHab() - count($habitaciones)) {
                        $casasOutput[] = $casa;
                    }
                }
            }

            return $casasOutput;

        }

    }

    function bubbleSortByTipoHab($array)
    {
        if (!$length = count($array)) {
            return $array;
        }
        for ($outer = 0; $outer < $length; $outer++) {
            for ($inner = 0; $inner < $length; $inner++) {
                if (($array[$inner]->getPeso() > $array[$outer]->getPeso())
                ) {
                    $tmp = $array[$outer];
                    $array[$outer] = $array[$inner];
                    $array[$inner] = $tmp;
                }
            }
        }
    }
}