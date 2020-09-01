<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Form\RoleFormType;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
{
    /**
     * @Route("/administration/roles", name="admin_role_index", methods={"GET","POST"})
     * @param RoleRepository $roleRepository
     * @param Request $request
     * @return Response
     */
    public function index(RoleRepository $roleRepository, Request $request)
    {
        return $this->render('admin/role/index.html.twig', [
            'roles' => $roleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/administration/roles/creation", name="admin_role_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('admin/role/create.html.twig', [
            'controller_name' => 'RoleController',
        ]);
    }
}
