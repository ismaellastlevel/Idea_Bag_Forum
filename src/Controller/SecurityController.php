<?php
/**
 * Controller login
 *
 * @package   src/Controller
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 *
 * @package App\Controller
 */
class SecurityController extends AbstractController
{


    /**
     * Login form
     *
     * @param AuthenticationUtils $authenticationUtils Authentication utils.
     *
     * @Route("/login", name="app_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one.
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user.
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }//end login()


    /**
     * Logout
     *
     * @Route("/logout", name="app_logout")
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        $this->addFlash('success', 'Vous êtes maintenant déconnecté !');
        return $this->redirectToRoute('app_login');

    }//end logout()


    /**
     * Form forget pwd
     *
     * @param Request                 $request        Request.
     * @param UserRepository          $userRepository Repo user.
     * @param MailerService           $mailerService  Mailer.
     * @param TokenGeneratorInterface $tokenGenerator Token.
     *
     * @Route("/oubli-pass", name="app_forgotten_password")
     *
     * @return Response
     */
    public function forgottenPass(Request $request, UserRepository $userRepository, MailerService $mailerService, TokenGeneratorInterface $tokenGenerator): Response
    {
        $form = $this->createForm(ResetPassFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() === true && $form->isValid() === true) {
            $data = $form->getData();
            $user = $userRepository->findOneBy(['email' => $data['email']]);
            if ($user === null) {
                $this->addFlash('warning', 'Adresse mail inconnu.');
                return $this->redirectToRoute('app_forgotten_password');
            }

            $token = $tokenGenerator->generateToken();
            try {
                $user->setResetPasswordToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                dump('Erreur : '.$e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $mailerService->sendMessage(
                'Forum - Réinitialisation de votre mot de passe.',
                'aa@oo.fr',
                $user->getEmail(),
                'emails/resetPass.html.twig',
                ['url' => $url]
            );
            $this->addFlash('success', 'Un mail vous a été envoyé avec les instructions.');
            return $this->redirectToRoute('app_login');
        }//end if

        return $this->render(
            'security/forgotten_password.html.twig',
            [
                'formResetPass' => $form->createView(),
            ]
        );

    }//end forgottenPass()


    /**
     * Form reset pwd
     *
     * @param Request                      $request         Request.
     * @param string                       $token           Token.
     * @param UserPasswordEncoderInterface $passwordEncoder Pwd encoder.
     *
     * @Route("/oubli-pass/{token}", name="app_reset_password")
     *
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetPasswordToken' => $token]);
        if ($user === null) {
            $this->addFlash('warning', 'Token inconnu !');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST') === true) {
            $user->setResetPasswordToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            if ($request->request->get('password') !== $request->request->get('confirmPassword')) {
                $this->addFlash('warning', 'Les mots de passe nne sont pas identiques !');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render(
                'security/reset_password.html.twig',
                ['token' => $token]
            );
        }

    }//end resetPassword()


}//end class
