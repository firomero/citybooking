<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 15/05/15
 * Time: 22:32
 */

namespace General\NomencladorBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;

class TipoHabRepository extends EntityRepository
{

    protected $columns = array('id', 'nombre', 'peso');

    public function queryEntity($options = array())
    {
        $em = $this->_em;
        $qb = $em->getRepository('NomencladorBundle:TipoHab')
            ->createQueryBuilder('a')
            ->distinct(true)
            ->select('a');

        if (array_key_exists('sSearch', $options)) {
            if ($options['sSearch'] != '') {
                $qb->andWhere(
                    new Orx(
                        array(
                            $qb->expr()->like('a.nombre', '\'%'.$options['sSearch'].'%\''),
                            $qb->expr()->like('a.peso', '\'%'.$options['sSearch'].'%\'')
                        )
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
             * @var Tipo de Habitacion $r
             * */
            array_push($dataExport, $r->toArray());
        }

        return $dataExport;

    }

    public function getFilteredCount(array $get)
    {
        /* DB table to use */
        $tableObjectName = 'NomencladorBundle:TipoHab';

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
} 