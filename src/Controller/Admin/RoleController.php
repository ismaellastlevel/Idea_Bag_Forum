<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Role;
use App\Form\Admin\RoleType;
use App\Manager\BaseManager;
use App\Manager\RoleManager;
use App\Manager\UserManager;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
            'formAjax' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manage", name="admin_role_manage", methods={"POST"})
     */
    public function manage(HttpFoundation\Request $request, RoleManager $manager)
    {
        $data = $request->request->get('role');
        $role = $manager->getOneRoleById((int) $data['id']);

        // Si le role n'éxiste pas, on créer une nouvelle instance pour réaliser un insert.
        if (!$role) {
            $role = new Role();
        }

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

    /**
     * @Route("/{id}", name="admin_role_fetch", methods={"GET"}, defaults={"id"=0}, requirements={"id"="\d+|__ID__"})
     */
    public function fetchRole(HttpFoundation\Request $request, RoleRepository $repository, int $id)
    {
        if ($request->isXmlHttpRequest()) {
            $role = $repository->findOneBy([
                'id' => $id
            ]);
            if ($role) {
                return $this->json(
                    [
                        'role' => $role
                    ],
                    200, []);
            }
            return new JsonResponse("No feedback for this role yet, Be the first ");
        }
        return new JsonResponse("This function is only available in AJAX");
    }

    /**
     * @Route("/{id}", name="admin_role_delete", methods={"DELETE"}, defaults={"id"=0}, requirements={"id"="\d+|__ID__"})
     * @param Role $role
     * @return JsonResponse
     */
    public function delete(Role $role, BaseManager $manager, HttpFoundation\Request $request)
    {
        $error = false;
        $message = "Role supprimé";

        if ($request->isXmlHttpRequest()) {
            if ($role) {
                try {
                    $manager->removeEntity($role, true);
                } catch (\Exception $e) {
                    $message = 'Erreur système' . $e->getMessage();
                    $error = true;
                }
                return new JsonResponse(
                    [
                        'error' => $error,
                        'message' => $message
                    ]
                );
            }
        }

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
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
