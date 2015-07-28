<?php


namespace Booking\BookingBundle\Manager;


use Booking\BookingBundle\Entity\Casa;
use Booking\BookingBundle\Entity\Habitacion;
use Booking\BookingBundle\Entity\Reservacion;
use Doctrine\ORM\EntityManager;
use General\NomencladorBundle\Entity\TipoHab;

class ReservacionManager
{

    protected $_em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
    }

    protected $map = array(
        array('unit' => 25, 'breakfast' => 4),
        array('unit' => 25, 'breakfast' => 8),
         array('unit' => 25, 'breakfast' => 8),
         array('unit' => 30, 'breakfast' => 12),
    );

    /**
     * @param Reservacion $entity
     */
    public function setPrecio(Reservacion $entity)
    {
       $em = $this->_em;
        $casaHabs = $em->getRepository('BookingBundle:Habitacion')->findByCasa($entity->getCasa());


        $entity->setTipoHab(array_map(
            function ($value) {

                /** @var Habitacion $value*/
                return $value->getTipo();
            }
            , $casaHabs));

        $habitaciones = $entity->getTipoHab()->toArray();
        $map = $this->map;
        $total = array_reduce($habitaciones,
            function($u,$d)use($map, $entity){


                /**
                 * @var TipoHab $d
                 *
                 * */
               $a =  $map[$d->getPeso()-1]['unit']*$entity->getNoches()+$map[$d->getPeso()-1]['breakfast'];
                $u+=$a;
                return $u;



        });


        $entity->setPrecio($total);

    }

    /**
     * Gets available houses
     * @param \DateTime $checkin
     * @param \DateTime $checkout
     * @param array $habitaciones
     * @return array
     */
    public function getCasasDisponibles(\DateTime $checkin, \DateTime $checkout, array $habitaciones)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->_em;
        $reservadas = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
            ->addSelect('casa')
            ->innerJoin('r.casa', 'casa')
            ->where('r.checkin <= :checkin')
            ->andWhere('r.checkout>= :checkout')
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
        if (count($reservadasId) > 0) {
            $casas = $qb->select('c')
                ->from('BookingBundle:Casa', 'c')
                ->where($qb->expr()->notIn('c.id', ':ids'))
                ->setParameter('ids', $reservadasId)
                ->getQuery()
                ->getResult();
        } else {
            $casas = $this->_em->getRepository('BookingBundle:Casa')->findAll();
        }

        return $this->filtrarCasa($casas, $habitaciones);
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
//            $this->bubbleSortByTipoHab($habitaciones);

            $this->sortBy($habitaciones,'desc');
            /*
             * Por cada casa pasada por parámetro, guardo sus habitaciones en un arreglo del que pueda ir eliminando
             * a medida que encuentre un match entre lo que tengo y lo que piden. Este análisis se ejecuta solo si la
             * cantidad de habitaciones de la casa es igual o mayor que la cantidad solicitada.
             * */
            foreach ($casas as $casa) {
                $casaHabs = $em->getRepository('BookingBundle:Habitacion')->findByCasa($casa);

                //Los tipos de habitaciones de una casa
                $checkArray = array_map(
                    function ($value) {

                        /** @var Habitacion $value*/
                        return $value->getTipo();
                    }
                    , $casaHabs);

                /**
                 * Ordeno las habitaciones solicitadas por el tipo, de mayor a menor teniendo en cuenta el Peso.
                 */
                  $this->sortBy($checkArray,'desc');

                /** @var Casa $casa*/
                if ($casa->getCantidadHab() >= count($habitaciones)) {
                    $counter=0;

                    foreach ($habitaciones as $habitacion) {
                        $result = current(array_filter($checkArray, function($value)use($habitacion){
                            if ($value->getPeso()>=$habitacion->getPeso()) {
                                return $value;
                            }
                        }));

                        if ($result) {
                            $index = $this->bySearch($result,$checkArray);
                            unset($checkArray[$index]);
                            $counter++;
                        }
                    }



                    if ($counter>=count($habitaciones)) {
                        $casasOutput[] = $casa->toArray();
                    }
                }
            }

            return $casasOutput;

        }

    }

    /**
     * QuickSort
     * @param $array
     * @return array
     */
    private function quicksort($array)
    {
        if (count($array) < 2) {
            return $array;
        }
        $left = $right = array();
        reset($array);
        $pivot_key = key($array);
        $pivot = array_shift($array);
        foreach ($array as $k => $v) {
            if ($v->getPeso() > $pivot->getPeso()) {
                $left[$k] = $v;
            } else {
                $right[$k] = $v;
            }
        }

        return array_merge($this->quicksort($left), array($pivot_key => $pivot), $this->quicksort($right));
    }

    /**
     * Bubblesort method
     * @param $array
     */
    private function bubbleSortByTipoHab($array)
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

    /**
     * Finds an element index
     * @param $el TipoHab to find
     * @param $collection array
     * @return int|string the key
     */
    protected  function bySearch($el,$collection){
       foreach ($collection as $index => $current) {
           if ($el->getId()==$current->getId()) {
               return $index;
           }
       }
       return -1;
   }

    /**
     * QuickSort de PHP
     * @param $array
     * @param string $direction
     * @return bool
     */
    function sortBy(&$array, $direction = 'asc')
    {


        usort($array,function($a,$b)use($direction){

            /**
             * @var TipoHab $a
             * @var TipoHab $b
             */
            if ($a->getPeso()==$b->getPeso()) {
                return 0;
            }

            return $direction == 'asc'?($a->getPeso()> $b->getPeso()?-1:1):($a->getPeso() < $b->getPeso()?-1:1);
        });

        return true;
    }

    /**
     * Intervals Day
     * @param $CheckIn
     * @param $CheckOut
     * @return float
     */
    public  function IntervalDays($CheckIn, $CheckOut){
        $CheckInX = explode("-", $CheckIn);
        $CheckOutX =  explode("-", $CheckOut);
        $date1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
        $date2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
        $interval =($date2 - $date1)/(3600*24);
        return  $interval ;
    }




}