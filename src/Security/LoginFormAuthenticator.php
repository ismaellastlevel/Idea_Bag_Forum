<?php
/**
 * Login form auth
 *
 * @package   src/Security
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LoginFormAuthenticator
 *
 * @package App\Security
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    /**
     * Entity manager
     *
     * @var EntityManagerInterface  Entity.
     */
    private $entityManager;

    /**
     * Url
     *
     * @var UrlGeneratorInterface Url.
     */
    private $urlGenerator;

    /**
     * Csrf
     *
     * @var CsrfTokenManagerInterface Csrf.
     */
    private $csrfTokenManager;

    /**
     * User
     *
     * @var UserPasswordEncoderInterface User.
     */
    private $passwordEncoder;

    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * LoginFormAuthenticator constructor.
     *
     * @param EntityManagerInterface $entityManager Entity.
     * @param UrlGeneratorInterface $urlGenerator Url.
     * @param CsrfTokenManagerInterface $csrfTokenManager Csrf.
     * @param UserPasswordEncoderInterface $passwordEncoder User pwd encoder.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator
    ) {
        $this->entityManager    = $entityManager;
        $this->urlGenerator     = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder  = $passwordEncoder;
        $this->translator       = $translator;
    }//end __construct()


    /**
     * Request
     *
     * @param Request $request Request.
     *
     * @return boolean
     */
    public function supports(Request $request)
    {
        return (self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST')) ? true : false;

    }//end supports()


    /**
     * Get credentials
     *
     * @param Request $request Request.
     *
     * @return array|mixed
     */
    public function getCredentials(Request $request)
    {
        $credentials = [
            'email'      => $request->request->get('email'),
            'password'   => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;

    }//end getCredentials()


    /**
     * Get current user
     *
     * @param mixed                 $credentials  Credentials.
     * @param UserProviderInterface $userProvider User.
     *
     * @return UserInterface|null
     *
     * @throws InvalidCsrfTokenException Csrf invalid.
     * @throws CustomUserMessageAuthenticationException Custom error msg.
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (($this->csrfTokenManager->isTokenValid($token)) === false) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->loadUserByUsername($credentials['email']);

        if (!$user) {
            $message = $this->translator->trans('emails could not be found');
            // Fail authentication with a custom error.
            throw new CustomUserMessageAuthenticationException($message);
        }

        if ($user->getActivationToken()) {
            $message = $this->translator->trans('You must activate your account');
            throw new CustomUserMessageAuthenticationException($message);
        }

        return $user;

    }//end getUser()


    /**
     * Check credentials
     *
     * @param mixed         $credentials Credentials.
     * @param UserInterface $user        User.
     *
     * @return boolean
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);

    }//end checkCredentials()


    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param array $credentials Credentials.
     *
     * @return string|null
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];

    }//end getPassword()


    /**
     * Auth success
     *
     * @param Request        $request     Request.
     * @param TokenInterface $token       Token.
     * @param string         $providerKey String.
     *
     * @return RedirectResponse|Response|null
     * @throws \Exception Exception.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey) === true) {
            return new RedirectResponse($targetPath);
        }

        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);

    }//end onAuthenticationSuccess()


    /**
     * Get login url
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);

    }//end getLoginUrl()


}//end class
