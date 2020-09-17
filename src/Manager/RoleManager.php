<?php


namespace App\Manager;


use App\Entity\Role;
use App\Repository\RoleRepository;
use App\Utils\ServiceContainer;
use Doctrine\ORM\EntityManagerInterface;

class RoleManager extends BaseManager
{

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct(EntityManagerInterface $em, ServiceContainer $sc)
    {
        $this->roleRepository = $em->getRepository(Role::class);
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