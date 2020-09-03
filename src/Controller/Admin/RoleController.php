<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Role;
use App\Form\Admin\RoleType;
use App\Manager\UserManager;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function manage(HttpFoundation\Request $request, UserManager $manager, ValidatorInterface $validator)
    {
        $data = $request->request->get('role');
        $role = new Role();
        $form = $this->createFormForJsonHandle(
            RoleType::class,
            $role,
            [
                'action' => $this->generateUrl('admin_role_manage'),
            ]
        );
        $form->submit($data);

        $error = false;
        $errorsBag = [];
        $message = 'Action enregistrée avec succès';

        if($form->isValid()) {
            try {
                $manager->persistEntity($role, true);
            } catch (\Exception $e) {
                $message = 'Erreur système' . $e->getMessage();
                $error = true;
            }
        } else {
            $errorsBag = $this->getErrorMessagesFromAjaxForm($form);
            $error = true;
            $message = 'Formulaire invalide, veuillez vérifier les données saisies';
        }

        return new HttpFoundation\JsonResponse(
            [
                'error' => $error,
                'message' => $message,
                'error_message_form' => $errorsBag,
            ],
            200
        );
    }

    private function getErrorsFromFormArray(FormInterface $form)
    {
        $errors = [];

        foreach ($form->all() as $child) {
            $errors = array_merge(
                $errors,
                $this->getErrorsFromFormArray($child)
            );
        }

        foreach ($form->getErrors() as $error) {
            $errors[$error->getCause()->getPropertyPath()] = $error->getMessage();
        }
        dump($errors);

        return $errors;
    }
}
