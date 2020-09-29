<?php


namespace App\Manager;


use App\Entity\Role;
use App\Repository\RoleRepository;
use App\Utils\ServiceContainer;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class RoleManager extends BaseManager
{

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct(ManagerRegistry $managerRegistry,ServiceContainer $sc)
    {
        parent::__construct($managerRegistry,$sc);
        $this->roleRepository = $this->em->getRepository(Role::class);
        dd($this->em);
    }

    public function getOneRoleById(int $id)
    {
        return $this->roleRepository->findOneBy(['id' => $id]);
    }

    public function getRolesList(array $filters)
    {
        return $this->roleRepository->getRolesList($filters);
    }


}
