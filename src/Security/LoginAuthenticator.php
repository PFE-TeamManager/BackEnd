<?php

namespace App\Security;

use App\Repository\MembreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

//AbstractFormLoginAuthenticator is where onAuthenticationFailure and supportsRememberMe lives
class LoginAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;//useful for redirection, by using getTargetPath
    
    private $membreRepository;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(MembreRepository $membreRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->membreRepository = $membreRepository;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }
    
    //called on every request
    public function supports(Request $request)
    {
        // do your work when we're POSTing to the login page
        // app_login is the name of the login page
        // The form has empty action so it posts to the same page
        return $request->attributes->get('_route') === 'app_login'
                && $request->isMethod('POST');
    }

    // if supports return true
    public function getCredentials(Request $request)
    {
        //dd($request->request->all()); (dump and die)
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            //'csrf_token' => $request->request->get('_csrf_token'),
        ];
        
        //set the email into the session
        //set session by email and LAST_USERNAME
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    //After getCredentials
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //if the csrfToken is differnet of authenticate then throw exception
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        //Don't forget Dependency Injection
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        //return user object or null
        return $this->membreRepository->findOneBy(['email' => $credentials['email']]);
    }

    //After getUser
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //if there is no target path in the session , fall to the homepage
        //if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            //return new RedirectResponse($targetPath);
            return new JsonResponse([
                'message' => "Success"
            ], 201);
        //}

        //return new RedirectResponse($this->router->generate('app_homepage'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey()
        ], 401);
    }

    protected function getLoginUrl()
    {
        //return $this->router->generate('app_login');
    }
}
