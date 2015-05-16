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

class AgenciaRepository extends EntityRepository
{

    protected $columns = array('id', 'nombre');

    public function queryEntity($options = array())
    {
        $em = $this->_em;
        $qb = $em->getRepository('NomencladorBundle:Agencia')
            ->createQueryBuilder('a')
            ->distinct(true)
            ->select('a');

        if ($options['sSearch'] != '') {
            $qb->andWhere(new Orx(array(
                $qb->expr()->like('a.nombre', '\'%' . $options['sSearch'] . '%\'')
            )));
        }

        if ($options['iSortCol_0'] = '') {
            $qb->orderBy('a.' . $options['iSortingCols'], $options['sSortDir_0']);

        }

        if ($options['iDisplayStart']!='') {
            $qb->setFirstResult($options['iDisplayStart']);
        }

        if ($options['iDisplayStart']!='') {
            $qb->setMaxResults($options['iDisplayLength']);
        }

        $result = $qb->getQuery()->getResult();
        $dataExport = array();

        foreach ($result as $r) {
            /**
             * @var Agencia $r
             * */
            array_push($dataExport, $r->toArray());
        }

        return $dataExport;

    }
} 