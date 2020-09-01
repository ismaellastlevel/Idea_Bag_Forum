<?php

namespace App\Form\Admin;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', Type\TextType::class, [
                'required' => true,
                'label' => 'role.code'
            ])
            ->add('label', Type\TextType::class, [
                'required' => true,
                'label' => 'common.label'
            ])
            ->add('description', Type\TextareaType::class, [
                'required' => false,
                'label' => 'common.description'
            ])
            ->add('id', Type\HiddenType::class, [
                'required' => false,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
