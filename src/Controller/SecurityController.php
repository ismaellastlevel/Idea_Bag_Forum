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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        return $this->redirectToRoute('app_login');

    }//end logout()


}//end class
