<?php
/**
 * Reset pass form
 *
 * @package   src/Form
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResetPassFormType
 *
 * @package App\Form
 */
class ResetPassFormType extends AbstractType
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
            'email',
            EmailType::class,
            [
                'attr' => ['class' => 'form-control'],
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
            // Configure your form options here.
            ]
        );

    }//end configureOptions()


}//end class
