<?php

namespace Booking\BookingBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;
use Proxies\__CG__\General\NomencladorBundle\Entity\TipoActividad;

class ActividadRepository extends EntityRepository {
    protected $columns = array('fecha', 'guia','total','pax', 'precioguia', 'tipoActividad', 'reservacion');

    /**
     * @param array $options
     * @return array
     */
    public function queryEntity($options = array())
    {
        $em = $this->_em;
        $columns = $this->columns;
        $qb = $em->getRepository('BookingBundle:Actividad')
            ->createQueryBuilder('a')
            ->distinct(true)
            ->select('a');

        if (array_key_exists('sSearch', $options)) {
            if ($options['sSearch'] != '') {
                $qb->andWhere(
                    new Orx(

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


                    )
                );
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

        $result = $qb->getQuery()->getResult();


        $dataExport = array();

        foreach ($result as $r) {
            /**
             * @var Actividad $r
             * */
            array_push($dataExport, $r->toArray());
        }

        return $dataExport;

    }

    /**
     * Count datatable filter
     * @param array $get
     * @return mixed
     */
    public function getFilteredCount(array $get)
    {
        /* DB table to use */
        $tableObjectName = 'BookingBundle:Actividad';

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
                if (isset($get['bSearchable_'.$i]) && $get['bSearchable_'.$i] == "true") {
                    $aLike[] = $cb->expr()->like('a.'.$this->columns[$i], '\'%'.$get['sSearch'].'%\'');
                }
            }
            if (count($aLike) > 0) {
                $cb->andWhere(new Orx($aLike));
            } else {
                unset($aLike);
            }
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
     * Closest Activity with a date and a type given
     * @param \DateTime $dateTime
     * @param $tipoActividad
     * @return array
     */
    public function closestActivity(\DateTime $dateTime, $tipoActividad){
        $em = $this->_em;
        $activities= $em->getRepository('BookingBundle:Actividad')->findBy(array('tipoActividad'=>$tipoActividad));
        return array_map(function(Actividad $actividad){
            return $actividad->toArray();
        },array_filter($activities,function(Actividad $activity)use($dateTime){
            return ($activity->getFecha()->format('m')==$dateTime->format('m'));
        }));
    }

} 