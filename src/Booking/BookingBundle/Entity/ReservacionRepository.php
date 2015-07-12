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
    protected $columns = array('id','checkin','checkout','noches','pax','habitacion','precio','confirmado','estado','observacion','agencia','casa','tipoHab');

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
            ->where($qb->expr()->eq('a.estado',':estado'))
            ->setParameter('estado',Reservacion::RESERVADA);

        if (array_key_exists('sSearch',$options)) {
            if ($options['sSearch'] != '') {
                $qb->andWhere(new Orx(

                /**
                 * @return array
                 */
                    call_user_func( function() use ($columns,$qb,$options){

                        $aLike = array();

                        foreach ($columns as $col) {

                            $aLike[] = $qb->expr()->like($col, '\'%' . $options['sSearch']['value'] . '%\'');
                        }

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
        if ( isset($get['sSearch']) && $get['sSearch'] != '' ){
            $aLike = array();
            for ( $i=0 ; $i<count($this->columns) ; $i++ ){
                if ( isset($get['bSearchable_'.$i]) && $get['bSearchable_'.$i] == "true" ){
                    $aLike[] = $cb->expr()->like($this->columns[$i], '\'%'. $get['sSearch'] .'%\'');
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

    /**
     * Logical delete
     * @param Reservacion $entity
     */
    public function delete(Reservacion $entity){
        $entity->setEstado(Reservacion::CANCELADA);
        $entity->setPrecio(0);

    }
}
