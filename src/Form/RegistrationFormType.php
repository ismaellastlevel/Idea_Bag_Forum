<?php
/**
 * Registration form
 *
 * @package   src/Form
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RegistrationFormType
 *
 * @package App\Form
 */
class RegistrationFormType extends AbstractType
{


    /**
     * Builder
     *
     * @param FormBuilderInterface $builder Builder.
     * @param array                $options Array options.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'attr' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Votre username',
                ],
            ]
        )->add(
            'email',
            EmailType::class,
            [
                'attr' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Votre email',
                ],
            ]
        )->add(
            'agreeTerms',
            CheckboxType::class,
            [
                'mapped'      => false,
                'constraints' => [
                    new IsTrue(
                        ['message' => 'You should agree to our terms.']
                    ),
                ],
            ]
        )->add(
            'plainPassword',
            RepeatedType::class,
            [
                'mapped'          => false,
                'type'            => PasswordType::class,
                'invalid_message' => 'Le mot de passe ne sont pas identiques..',
                'required'        => true,
                'first_options'   => [
                    'label'       => 'Password',
                    'attr'        => [
                        'class'       => 'form-control',
                        'placeholder' => 'Votre mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank(['message' => 'Vous devez renseigner votre mot de passe.']),
                    ],
                ],
                'second_options'  => [
                    'attr'        => [
                        'class'       => 'form-control mb-3',
                        'placeholder' => 'Votre confirmation du mot de passe',
                    ],
                    'label'       => 'Password confirmation',
                    'constraints' => [new NotBlank(['message' => 'Vous devez renseigner votre confirmation du mot de passe.'])],
                ],
            ]
        );

    }//end buildForm()


    /**
     * Configuration
     *
     * @param OptionsResolver $resolver Resolver.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );

    }//end configureOptions()


}//end class
