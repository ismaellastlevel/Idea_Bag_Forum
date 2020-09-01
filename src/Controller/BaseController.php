<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends AbstractController
{

    public function createFormForJsonHandle($formType, $entity = [], $options = [])
    {
        return $this->createForm($formType, $entity, $options);
    }

    public function getFormJsonData(HttpFoundation\Request $request, $fetchAssoc = false)
    {
        $data = json_decode($request->getContent(), $fetchAssoc);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }

        return $data;
    }

}