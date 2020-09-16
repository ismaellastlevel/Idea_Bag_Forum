<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoleRepository extends BaseRepository
{

    /**
     * Return roles for DataTables
     *
     * @param array $filters
     *
     * @return array
     */
    public function getRolesList(array $filters): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id', 'r.label', 'r.description')
        ;

        $countFiltered = $this->getQbTotal($qb);

        $this->searchByCol($qb, $filters, ['label', 'description'], 'r');

        $this->setOrdered($qb, $filters, ['label', 'description'], 'r');
        $this->setPagination($qb, $filters);
        $result = $qb->getQuery()->getArrayResult();

        return $this->renderDataTableList($result, $countFiltered, $filters);
    }

}
