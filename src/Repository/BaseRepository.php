<?php
/**
 * Common methods for repositories
 *
 * @package   App\Repository
 * @version   0.0.1
 * @author    Rayzen-dev <rayzen.dev@gmail.com>
 * @copyright no-copyrights
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class BaseRepository
 */
class BaseRepository extends EntityRepository
{

    /** @var integer $offset */
    protected $offset = 0;
    /** @var integer $limit */
    protected $limit = 100;

    public function getQbTotal($qb, string $prefixForCount = 'q')
    {
        $qb1 = clone $qb;
        $prefix = $prefixForCount == 'q' ? $qb->getRootAlias() : $prefixForCount;

        return $qb1->select($qb->expr()->count( 'DISTINCT '.$prefix ))
            ->getQuery()->getSingleScalarResult()
            ;
    }

    public function setOrdered($qb, array $filters, array $fields, $index = 'q')
    {
        if (isset($filters['order']) && isset($filters['columns'])) {
            foreach ($filters['order'] as $ord) {
                if (isset($ord['column']) && isset($ord['dir'])) {
                    $col = isset($filters['columns'][$ord['column']]) ? $filters['columns'][$ord['column']] : null;
                    if ($col && isset($col['data']) && in_array($col['data'], $fields)) {
                        if (isset($col['orderable']) && $col['orderable']) {
                            $index1 = !strstr($col['data'], '_') ? $index.'.'.$col['data'] : $col['data'];
                            $qb->orderBy($index1, $ord['dir']);
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function setPagination($qb, array $filters)
    {
        $offset = isset($filters['start']) ? $filters['start'] : $this->offset;
        $limit = isset($filters['length']) ? $filters['length'] : $this->limit;
        $qb->setFirstResult($offset)
            ->setMaxResults($limit);
    }

    protected function renderDataTableList(array $result, int $countFiltered, array $filters, string $prefix = 'q', string $prefixForCount = null)
    {
        $data = [];
        $data['draw'] = isset($filters['draw']) ? $filters['draw'] : 1;
        $data['recordsTotal']  = $this->getTotal($prefix, $prefixForCount);
        $data['recordsFiltered'] = $countFiltered;
        $data['data'] = $result;

        return $data;
    }

    public function getTotal(string $prefix = 'q', string $prefixForCount = null)
    {
        $qb = $this->createQueryBuilder($prefix);

        if ($prefixForCount) {
            $qb->select($qb->expr()->count( 'DISTINCT '.$prefixForCount ));
        } else {
            $qb->select($qb->expr()->count( 'DISTINCT '.$prefix ));
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function searchByCol($qb, array $filters, array $fields, $index = 'q', $operation = 'LIKE')
    {
        if (isset($filters['columns'])) {
            foreach ($filters['columns'] as $key => $col) {
                if (isset($col['data']) && isset($col['search']) && isset($col['searchable']) && $col['searchable']) {
                    if (isset($col['search']['value']) && $col['search']['value'] != "" && in_array($col['data'], $fields)) {
                        $explode = explode('__OR__', $col['data']);
                        if (count($explode) == 1) {
                            $index1 = !strstr($col['data'], '_') ? $index.'.'.$col['data'] : str_replace('_', '.', $col['data']);
                            $qb->andWhere($index1.' '.$operation.($operation == 'IN' ? ' (:search_'.$key.')' : ' :search_'.$key));
                        } else {
                            $orX = $qb->expr()->orX();
                            foreach($explode as $index1) {
                                $index1 = !strstr($index1, '_') ? $index.'.'.$index1 : str_replace('_', '.', $index1);
                                $orX->add($index1.' '.$operation.($operation == 'IN' ? ' (:search_'.$key.')' : ' :search_'.$key));
                            }
                            $qb->andWhere($orX);
                        }

                        $qb->setParameter('search_'.$key, $operation == 'LIKE' ? '%'.$col['search']['value'].'%' : ($operation == 'IN' ? explode(',', $col['search']['value']) : $col['search']['value']));
                    }
                }
            }

            return true;
        }

        return false;
    }

}