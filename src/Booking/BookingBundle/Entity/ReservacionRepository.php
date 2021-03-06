<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;
use General\NomencladorBundle\Entity\TipoHab;

/**
 * ReservacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservacionRepository extends EntityRepository
{
    protected $columns = array('id', 'checkin', 'checkout', 'noches', 'pax', 'habitacion', 'precio', 'confirmado', 'estado', 'observacion', 'agencia', 'casa','cliente');

    const NORMAL = 25;
    const EXTRA = 30;
    const BREAKFAST = 4;

    /**
     * @param array $options
     * @return array
     */
    public function queryEntity($options = array())
    {
        $columns = &$this->columns;

        $em = $this->_em;
        $qb = $em->getRepository('BookingBundle:Reservacion')
            ->createQueryBuilder('a');
        $qb->distinct(true)
            ->select('a')
            ->leftJoin('NomencladorBundle:Agencia', 'ag', 'WITH', 'a.agencia = ag.id')
            ->leftJoin('BookingBundle:Casa', 'cs', 'WITH', 'a.casa = cs.id')
            ->leftJoin('BookingBundle:Cliente', 'cl', 'WITH', 'a.cliente = cl.id')
            ->where($qb->expr()->eq('a.estado', ':estado'))
            ->setParameter('estado', Reservacion::RESERVADA);

        if (array_key_exists('sSearch', $options)) {
            if ($options['sSearch'] != '') {
                $qb->andWhere(new Orx(

                /**
                 * @return array
                 */
                    call_user_func(function () use ($columns, $qb, $options, $em) {

                        $aLike = array_map(function ($col) use ($options, $qb, $em) {
                            switch ($col) {
                                case 'agencia':
                                    return $qb->expr()->like('ag.nombre', '\'%' . $options['sSearch'] . '%\'');
                                case 'casa':
                                    return $qb->expr()->like('cs.nombre', '\'%' . $options['sSearch'] . '%\'');
                                case 'cliente':
                                    return $qb->expr()->like('cl.nombre', '\'%' . $options['sSearch'] . '%\'');
                                default:
                                    return $qb->expr()->like('a.' . $col, '\'%' . $options['sSearch'] . '%\'');
                            }

                        }, $columns);


                        return $aLike;
                    })

                ));
            }
        }

        if (isset($options['iDisplayStart']) && $options['iDisplayLength'] != '-1') {
            $qb->setFirstResult((int)$options['iDisplayStart'])
                ->setMaxResults((int)$options['iDisplayLength']);
        }


        if (array_key_exists('iDisplayLength', $options)) {
            if ($options['iDisplayLength'] != '') {
                $qb->setMaxResults($options['iDisplayLength']);
            }
        }

//        die(var_dump($qb->getQuery()->getDQL()));
        $result = $qb->getQuery()->getResult();
        $dataExport = array();

        foreach ($result as $r) {
            /**
             * @var Reservacion $r
             * */
            array_push($dataExport, $r->toArray());
        }

        return $dataExport;

    }

    /**
     * @param array $get
     * @return mixed
     */
    public function getFilteredCount(array $get)
    {
        /* DB table to use */
        $tableObjectName = 'BookingBundle:Reservacion';

        $cb = $this->getEntityManager()
            ->getRepository($tableObjectName)
            ->createQueryBuilder('a')
            ->select("count(a.id)");

        /*
        * Filtering
        * NOTE this does not match the built-in DataTables filtering which does it
        * word by word on any field. It's possible to do here, but concerned about efficiency
        * on very large tables, and MySQL's regex functionality is very limited
        */
        if (isset($get['sSearch']) && $get['sSearch'] != '') {
            $aLike = array();
            for ($i = 0; $i < count($this->columns); $i++) {
                if (isset($get['bSearchable_' . $i]) && $get['bSearchable_' . $i] == "true") {
                    $aLike[] = $cb->expr()->like('a.' . $this->columns[$i], '\'%' . $get['sSearch'] . '%\'');
                }
            }
            if (count($aLike) > 0) $cb->andWhere(new Orx($aLike));
            else unset($aLike);
        }

        /*
         * SQL queries
         * Get data to display
         */
        $query = $cb->getQuery();
        $aResultTotal = $query->getResult();
        return $aResultTotal[0][1];
    }

    /**
     * Logical delete
     * @param Reservacion $entity
     */
    public function delete(Reservacion $entity)
    {
        $entity->setEstado(Reservacion::CANCELADA);
        $entity->setPrecio(0);

    }
}
