<?php


namespace App\Manager;


use App\Entity\Role;
use App\Repository\RoleRepository;

class RoleManager extends BaseManager
{
    public function getOneRoleById(int $id)
    {
        return $this->fetchOneEntityById(Role::class, $id);
    }
}