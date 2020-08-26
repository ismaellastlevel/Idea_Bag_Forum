<?php
/**
 * Class RegistrationController
 *
 * @package   src/Controller/RegistrationController.php
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class RegistrationController
 *
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{


    /**
     * Register a new User
     *
     * @param Request                      $request         Request.
     * @param UserPasswordEncoderInterface $passwordEncoder User pwd encoder.
     * @param TokenGeneratorInterface      $tokenGenerator  Token.
     * @param MailerService                $mailerService   Send mail.
     *
     * @return Response
     *
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator, MailerService $mailerService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, ['validation_groups' => 'registration']);
        $form->handleRequest($request);

        if ($form->isSubmitted() === true && $form->isValid() === true) {
            // Encode the plain password.
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setActivationToken($tokenGenerator->generateToken());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $mailerService->sendMessage(
                'Forum - Vous devez activer votre compte.',
                'aa@oo.fr',
                $user->getEmail(),
                'emails/activation.html.twig',
                [
                    'token' => $user->getActivationToken(),
                    'user'  => $user,
                ]
            );

            return $this->redirectToRoute('app_login');
        }//end if

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );

    }//end register()


    /**
     * Account activation
     *
     * @param string                 $token          Token.
     * @param UserRepository         $userRepository Repo user.
     * @param EntityManagerInterface $entityManager  Entity manager.
     *
     * @return RedirectResponse|NotFoundHttpException
     * @throws \Exception Exception.
     *
     * @Route("/activation/{token}", name="activation")
     */
    public function activation(string $token, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findOneBy(['activation_token' => $token]);
        if ($user === false) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas.");
        }

        $user->setActivationToken(null);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_login');

    }//end activation()


}//end class
