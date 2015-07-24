<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 5/06/15
 * Time: 23:33
 */

namespace Booking\BookingBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;

class HabitacionRepository extends EntityRepository{
    protected $columns = array('id','casa','tipo');
    public function queryEntity($options = array())
    {
        $columns = &$this->columns;

        $em = $this->_em;
        $qb = $em->getRepository('BookingBundle:Habitacion')
            ->createQueryBuilder('h')
            ->innerJoin('h.casa','c')
            ->innerJoin('h.tipo','t')
            ->distinct(true)
            ->select('h');

        if (array_key_exists('sSearch',$options)) {
            if ($options['sSearch'] != '') {
                $qb->andWhere(new Orx(

                /**
                 * @return array
                 */
                    call_user_func( function() use ($columns,$qb,$options){

                        $aLike = array(
                            $qb->expr()->like('c.nombre' , '\'%' . $options['sSearch'] . '%\''),
                            $qb->expr()->like('t.nombre' , '\'%' . $options['sSearch'] . '%\''),

                        );

                        return $aLike;
                    })

                ));
            }
        }

        if ( isset( $options['iDisplayStart'] ) && $options['iDisplayLength'] != '-1' ){
            $qb->setFirstResult( (int)$options['iDisplayStart'] )
                ->setMaxResults( (int)$options['iDisplayLength'] );
        }


        if (array_key_exists('iDisplayLength',$options)) {
            if ($options['iDisplayLength']!='') {
                $qb->setMaxResults($options['iDisplayLength']);
            }
        }

        $result = $qb->getQuery()->getResult();
        $dataExport = array();

        foreach ($result as $r) {
            /**
             * @var Habitacion $r
             * */
            array_push($dataExport, $r->toArray());
        }

        return $dataExport;

    }

    public function getFilteredCount(array $get)
    {
        /* DB table to use */
        $tableObjectName = 'BookingBundle:Habitacion';

        $cb = $this->getEntityManager()
            ->getRepository($tableObjectName)
            ->createQueryBuilder('h')
            ->innerJoin('h.casa','c')
            ->innerJoin('h.tipo','t')
            ->select("count(h.id)");

        /*
        * Filtering
        * NOTE this does not match the built-in DataTables filtering which does it
        * word by word on any field. It's possible to do here, but concerned about efficiency
        * on very large tables, and MySQL's regex functionality is very limited
        */
        if ( isset($get['sSearch']) && $get['sSearch'] != '' ){
            $aLike = array();
            for ( $i=0 ; $i<count($this->columns) ; $i++ ){
                if ( isset($get['bSearchable_'.$i]) && $get['bSearchable_'.$i] == "true" ){
                    $aLike = array(
                        $cb->expr()->like('c.nombre' , '\'%' . $get['sSearch'] . '%\''),
                        $cb->expr()->like('t.nombre' , '\'%' . $get['sSearch'] . '%\''),

                    );
                }
            }
            if(count($aLike) > 0) $cb->andWhere(new Orx($aLike));
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
} 