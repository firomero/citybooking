<?php


namespace Booking\BookingBundle\Manager;

use Booking\BookingBundle\Entity\BookedHab;
use Booking\BookingBundle\Entity\Casa;
use Booking\BookingBundle\Entity\Habitacion;
use Booking\BookingBundle\Entity\Reservacion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Orx;
use General\NomencladorBundle\Entity\TipoHab;
use Proxies\__CG__\General\NomencladorBundle\Entity\TipoActividad;

class ReservacionManager
{
    protected $_em;
    protected $map = array(
        array('unit' => 25, 'breakfast' => 4),
        array('unit' => 25, 'breakfast' => 8),
        array('unit' => 25, 'breakfast' => 8),
        array('unit' => 30, 'breakfast' => 12),
    );

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
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
            ->where('r.checkin <= :checkin')
            ->andWhere('r.checkout>= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin,
                )
            )
            ->getQuery()
            ->getResult();

        $reservada1 = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
            ->where('r.checkin <= :checkin')
            ->andWhere('r.checkout>= :checkin')
            ->andWhere('r.checkin<= :checkout')
            ->andWhere('r.checkout<= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin
                )
            )
            ->getQuery()
            ->getResult();

        $reservada2 = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
            ->where('r.checkin >= :checkin')
            ->andWhere('r.checkout>= :checkout')
            ->andWhere('r.checkin<= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin
                )
            )
            ->getQuery()
            ->getResult();

        $reservadas = array_merge($reservadas, $reservada1, $reservada2);

        $reservadasId = array_unique(array_map(function ($reservada) {
            /**
             * @var Reservacion $reservada
             */
            return $reservada->getCasa()->getId();
        }, $reservadas));

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

//todo en esta parte llamar a las habitaciones disponibles según las fechas disponibles en el servidor, esta parte sería en la llamada de las casas que en la fecha solicitada ya están reservadas

        $free= $this->filtrarCasa($casas, $habitaciones);
        $booked = $this->FreeBookedRoom($checkin, $checkout, $habitaciones);
        return array_unique_callback(array_merge($free, $booked), function ($h) {return $h[1];});
    }

    /**
     * @param $casas
     * @param $habitaciones
     * @return array
     */
    protected function filtrarCasa($casas, $habitaciones)
    {
        {
            $casasOutput = array();

            /**
             * Ordeno las habitaciones solicitadas por el tipo, de mayor a menor teniendo en cuenta el Peso.
             */


            $this->sortBy($habitaciones, 'desc');
            /*
             * Por cada casa pasada por parámetro, guardo sus habitaciones en un arreglo del que pueda ir eliminando
             * a medida que encuentre un match entre lo que tengo y lo que piden. Este análisis se ejecuta solo si la
             * cantidad de habitaciones de la casa es igual o mayor que la cantidad solicitada.
             * */
            foreach ($casas as $casa) {
                //                $casaHabs = $em->getRepository('BookingBundle:Habitacion')->findByCasa($casa);
                /**
                 * @var Casa $casa
                 */
                $casaHabs = $casa->getHabitaciones()->toArray();

                //Los tipos de habitaciones de una casa
                $checkArray = array_map(
                    function ($value) {

                        /** @var Habitacion $value */
                        return $value->getTipo();
                    }, $casaHabs);

                /**
                 * Ordeno las habitaciones solicitadas por el tipo, de mayor a menor teniendo en cuenta el Peso.
                 */
                $this->sortBy($checkArray, 'desc');

                /** @var Casa $casa */
                if ($casa->getCantidadHab() >= count($habitaciones)) {
                    $counter = 0;

                    foreach ($habitaciones as $habitacion) {
                        $result = current(array_filter($checkArray, function ($value) use ($habitacion) {
                            if ($value->getPeso() >= $habitacion->getPeso()) {
                                return $value;
                            }
                        }));

                        if ($result) {
                            $index = $this->bySearch($result, $checkArray);
                            unset($checkArray[$index]);
                            $counter++;
                        }
                    }


                    if ($counter >= count($habitaciones)) {
                        $casasOutput[] = $casa->toArray();
                    }
                }
            }


            return $casasOutput;


        }
    }

    /**
     * QuickSort de PHP
     * @param $array
     * @param string $direction
     * @return bool
     */
    protected function sortBy(&$array, $direction = 'asc')
    {
        usort($array, function ($a, $b) use ($direction) {

            /**
             * @var TipoHab $a
             * @var TipoHab $b
             */
            if ($a->getPeso() == $b->getPeso()) {
                return 0;
            }

            return $direction == 'asc' ? ($a->getPeso() > $b->getPeso() ? -1 : 1) : ($a->getPeso() < $b->getPeso() ? -1 : 1);
        });

        return true;
    }

    /**
     * Finds an element index
     * @param $el TipoHab to find
     * @param $collection array
     * @return int|string the key
     */
    protected function bySearch($el, $collection)
    {
        foreach ($collection as $index => $current) {
            if ($el->getId() == $current->getId()) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * Save the book tru if success elsewhere an exception is thrown
     * @param Reservacion $reservacion
     * @return bool
     * @throws \Exception
     */
    public function save(Reservacion $reservacion)
    {
        $em = $this->_em;
        $rhabs = $reservacion->getTipoHab()->toArray();

        $chabs = $reservacion->getCasa()->getHabitaciones()->toArray();

        $chabs = array_filter($chabs, function ($hb) use ($reservacion) {
            if (!$this->isBooked($hb, $reservacion->getCheckin(), $reservacion->getCheckout())) {
                return $hb;
            }
        });

        if ($reservacion->getPrecio() == null) {
            $this->setPrecio($reservacion);
        }
        /**
         * @var EntityManager $em
         */
        $em->beginTransaction();
        $this->sortBy($rhabs);
        $this->sortHabs($chabs);

        while (count($chabs)>0&&count($rhabs)>0) {
            $book = new BookedHab();
            $rt = array_shift($rhabs);
            $cht = current($chabs);
            $index = count($chabs)<=1?0: binary_search_uncentered_callable($chabs, 0, count($chabs), $rt,
                function ($object) {
                    if ($object instanceof TipoHab) {
                        return $object->getPeso();
                    } else {
                        return $object->getTipo()->getPeso();
                    }
                });
            if ($index!=-1) {
                $cht = $chabs[$index];
                unset($chabs[$index]);
            } else {
                array_shift($chabs);
            }
            $book->setHab($cht);
            $book->setCheckin($reservacion->getCheckin());
            $book->setCheckout($reservacion->getCheckout());
            $em->persist($book);
        }

        $em->persist($reservacion);

        try {
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
        return true;
    }

    /**
     * @param Habitacion $hb
     * @param \DateTime $checkin
     * @param \DateTime $checkout
     * @return bool
     */
    protected function isBooked(Habitacion $hb, \DateTime $checkin, \DateTime $checkout)
    {
        $em = $this->_em;
        $booked = $em->createQueryBuilder('bk');
        $dash= $booked->select('bk')
            ->from('BookingBundle:BookedHab', 'bk')
            ->where('bk.hab=:hb')
            ->setParameter('hb', $hb)
            ->getQuery()->getResult()
            ;



        $o = array_filter($dash, function ($b) use ($checkin,$checkout) {
            /**
             * @var BookedHab $b
             */
            if ($b->getCheckin()<=$checkin && $b->getCheckout()>=$checkout) {
                return $b;
            }

            if ($b->getCheckin()<=$checkout && $b->getCheckout()>=$checkout) {
                return $b;
            }

            if ($b->getCheckin()<=$checkin && $b->getCheckout()>=$checkin) {
                return $b;
            }
        });

        return count($o)>0;
    }

    /**
     * @param Reservacion $entity
     */
    public function setPrecio(Reservacion $entity)
    {
        $em = $this->_em;
        $casaHabs = $em->getRepository('BookingBundle:Habitacion')->findByCasa($entity->getCasa());


        $entity->setTipoHab(array_map(
            function ($value) {

                /** @var Habitacion $value */
                return $value->getTipo();
            }, $casaHabs));

        $habitaciones = $entity->getTipoHab()->toArray();
        $map = $this->map;
        $total = array_reduce($habitaciones,
            function ($u, $d) use ($map, $entity) {


                /**
                 * @var TipoHab $d
                 *
                 * */
                $a = $map[$d->getPeso() - 1]['unit'] * $entity->getNoches() + $map[$d->getPeso() - 1]['breakfast'];
                $u += $a;
                return $u;


            });


        $entity->setPrecio($total);
    }

    /**
     * @param $array
     * @param string $direction
     * @return bool
     */
    protected function sortHabs(&$array, $direction = 'asc')
    {
        usort($array, function ($a, $b) use ($direction) {

            /**
             * @var Habitacion $a
             * @var Habitacion $b
             */
            if ($a->getTipo()->getPeso() == $b->getTipo()->getPeso()) {
                return 0;
            }

            return $direction == 'asc' ? ($a->getTipo()->getPeso() > $b->getTipo()->getPeso() ? -1 : 1) : ($a->getTipo()->getPeso() < $b->getTipo()->getPeso() ? -1 : 1);
        });

        return true;
    }

    /**
     * Intervals Day
     * @param $CheckIn
     * @param $CheckOut
     * @return float
     */
    protected function IntervalDays($CheckIn, $CheckOut)
    {
        $CheckInX = explode("-", $CheckIn);
        $CheckOutX = explode("-", $CheckOut);
        $date1 = mktime(0, 0, 0, $CheckInX[1], $CheckInX[2], $CheckInX[0]);
        $date2 = mktime(0, 0, 0, $CheckOutX[1], $CheckOutX[2], $CheckOutX[0]);
        $interval = ($date2 - $date1) / (3600 * 24);
        return $interval;
    }

    /**
     * @deprecated Use FreeBookedRoom instead.
     * @param \DateTime $checkin
     * @param \DateTime $checkout
     * @param array $habitaciones
     * @return array
     */
    protected function availableBookedHab(\DateTime $checkin, \DateTime $checkout, array $habitaciones)
    {
        $this->sortBy($habitaciones, 'desc');
        /**
         * @var EntityManager $em
         */
        $em = $this->_em;
        $reservadas = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
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

        $reservada1 = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
            ->where('r.checkin <= :checkin')
            ->andWhere('r.checkout>= :checkin')
            ->andWhere('r.checkin<= :checkout')
            ->andWhere('r.checkout<= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin
                )
            )
            ->getQuery()
            ->getResult();

        $reservada2 = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
            ->where('r.checkin >= :checkin')
            ->andWhere('r.checkout>= :checkout')
            ->andWhere('r.checkin<= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin
                )
            )
            ->getQuery()
            ->getResult();

        $reservadas = array_merge($reservadas, $reservada1, $reservada2);


        $closure = function ($val) {
            /**
             * @var Reservacion $val
             */
            return array(
                'casa' => $val->getCasa(),
                'habs' => call_user_func(function () use ($val) {
                    $hbs = array_map(function ($h) {
                            /** @var Habitacion $h */
                            return $h->getTipo();
                        }, $val->getCasa()->getHabitaciones()->toArray());
                    $r = $val->getTipoHab()->toArray();
                    $this->sortBy($hbs, 'desc');
                    $this->sortBy($r, 'desc');

                    if (sizeof($hbs) == sizeof($r)) {
                        return array();
                    }
                    $a = array_udiff($hbs, $r, function ($a, $b) {
                        if ($a->getPeso() < $b->getPeso()) {
                            return -1;
                        } elseif ($a->getPeso() > $b->getPeso()) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });
                    return $a;
                })
            );
        };


        $c = array_filter(array_unique_callback(array_map($closure, $reservadas), function ($casa) {
                return $casa['casa']->getNombre();
            }), function ($var) use ($habitaciones) {
            if (count($var['habs']) >= count($habitaciones)) {
                $counter = 0;
                foreach ($habitaciones as $hb) {
                    $result = current(array_filter($var['habs'], function ($value) use ($hb) {
                        if ($value->getPeso() >= $hb->getPeso()) {
                            return $value;
                        }
                    }));

                    if ($result) {
                        $index = $this->bySearch($result, $var['habs']);
                        unset($var['habs'][$index]);
                        $counter++;
                    }

                    if ($counter >= count($habitaciones)) {
                        return $var['casa']->toArray();
                    }
                }
            }
        });

        return array_map(function ($item) {
            return $item['casa']->toArray();
        }, $c);
    }

    /**
     * Returns Houses with availables book instances
     * @param \DateTime $checkin
     * @param \DateTime $checkout
     * @param array $habitaciones
     * @return array
     */
    public function FreeBookedRoom(\DateTime $checkin, \DateTime $checkout, array $habitaciones)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->_em;
        /**
         * @var EntityManager $em
         */
        $em = $this->_em;
        $reservadas = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
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

        $reservada1 = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
            ->where('r.checkin <= :checkin')
            ->andWhere('r.checkout>= :checkin')
            ->andWhere('r.checkin<= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin
                )
            )
            ->getQuery()
            ->getResult();

        $reservada2 = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('r')
            ->where('r.checkin >= :checkin')
            ->andWhere('r.checkout>= :checkout')
            ->andWhere('r.checkin<= :checkout')
            ->setParameters(
                array(
                    'checkout' => $checkout,
                    'checkin' => $checkin
                )
            )
            ->getQuery()
            ->getResult();

        $casas = array_map(
            function ($r) {
                /**@var Reservacion $r*/ return $r->getCasa();
            },
            array_unique_callback(
                array_merge($reservadas, $reservada1, $reservada2),
                function ($val) {
                    /**@var Reservacion $val*/return $val->__toString();
                }
            )
        );

        $this->sortBy($habitaciones, 'desc');
        $casas = array_filter($casas, function ($casa) use ($habitaciones, $checkin, $checkout,$em) {
            /**
             * @var Casa $casa
             */
            $hbs = array_merge($em->getRepository('BookingBundle:Casa')->LockedDayHabs($checkin, $casa), $em->getRepository('BookingBundle:Casa')->LockedDayHabs($checkout, $casa));
            $hbs = array_unique_callback($hbs,
                function ($hb) {return $hb->getId();}
            );



            $all = $casa->getHabitaciones()->toArray();

            $diff = array_udiff($all, $hbs, function ($a, $b) {
                if ($a->getTipo()->getPeso() < $b->getTipo()->getPeso()) {
                    return -1;
                } elseif ($a->getTipo()->getPeso() > $b->getTipo()->getPeso()) {
                    return 1;
                } else {
                    return 0;
                }
            });

            $types = array_map(function ($hb) {return $hb->getTipo();}, $diff);
            $this->sortBy($types, 'desc');

            if (count($types)>=count($habitaciones)) {
                $counter = 0;
                foreach ($habitaciones as $habitacion) {
                    $result = current(array_filter($types, function ($value) use ($habitacion) {
                        if ($value->getPeso() >= $habitacion->getPeso()) {
                            return $value;
                        }
                    }));

                    if ($result) {
                        $index = $this->bySearch($result, $types);
                        unset($types[$index]);
                        $counter++;
                    }
                }

                if ($counter >= count($habitaciones)) {
                    return $casa;
                }
            }

        });

        return array_map(function ($casa) { return $casa->toArray();}, $casas);
    }

    /**
     * @deprecated use global sort
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
     * @deprecated
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
}
