<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Role;
use App\Form\Admin\RoleType;
use App\Manager\UserManager;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RoleController
 *
 * @Route("/admin/roles")
 */
class RoleController extends BaseController
{
    /**
     * @param RoleRepository         $roleRepository
     * @param HttpFoundation\Request $request
     *
     * @return HttpFoundation\Response
     *
     * @Route("/", name="admin_role_index", methods={"GET","POST"})
     */
    public function index(RoleRepository $roleRepository, HttpFoundation\Request $request)
    {
        $form = $this->createFormForJsonHandle(
            RoleType::class,
            new Role(),
            [
                'action' => $this->generateUrl('admin_role_manage'),
            ]
        );

        return $this->render('admin/role/index.html.twig', [
            'roles' => $roleRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manage", name="admin_role_manage", methods={"POST"})
     */
    public function manage(HttpFoundation\Request $request, UserManager $manager)
    {
        $data = $request->request->get('role');
        $form = $this->createFormForJsonHandle(
            RoleType::class,
            new Role(),
            [
                'action' => $this->generateUrl('admin_role_manage'),
            ]
        );
        $form->submit($data);

        $error = false;
        $message = null;

        if($form->isValid()) {
            $role = $form->getData();
            $manager->persistEntity($role, true);

            $message = 'Action enregistrée avec succès';
        } else {
            $error = true;
            $message = 'Formulaire invalide, veuillez vérifier les données saisies';
        }

        return new HttpFoundation\JsonResponse(
            [
                'error' => $error,
                'message' => $message,
            ],
            200
        );

    }
}
