<?php


namespace App\Controller;


use App\Utils\ServiceContainer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{

    private $validator;

    private $sc;

    public function __construct(ValidatorInterface $validator, ServiceContainer $serviceContainer)
    {
        $this->validator = $validator;
        $this->sc = $serviceContainer;
    }

    public function createFormForJsonHandle($formType, $entity = [], $options = [])
    {
        $form = $this->createForm($formType, $entity, $options);

        return $form;
    }

    /**
     * Permet de retourner les erreurs d'un formulaire envoyé avec de l'ajax.
     * Format: ['nomFormulaire.inputName' => 'messageErreur']
     *
     * @param FormInterface $form Le formulaire envoyé.
     *
     * @return array
     */
    public function getErrorMessagesFromAjaxForm(FormInterface $form): array
    {
        $data = $form->getData();
        $errorsBag = $this->validator->validate($data);
        $errors = [];

        foreach ($errorsBag as $error) {
            /** @var ConstraintViolation $error  */
            $errors[$form->getName() . '.' . $error->getPropertyPath()] = $this->sc->translate($error->getMessage());
        }

        return $errors;
    }

}