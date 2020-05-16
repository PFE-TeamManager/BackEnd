<?php

namespace App\Security;

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginAuthenticator extends AbstractGuardAuthenticator
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        //It will be used when the user posts to the /login endpoint!
        return $request->get("_route") === "api_login" && $request->isMethod("POST");
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password')
        ];

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //This function should simply return the user that is trying to log in
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //check password with the user from getUser
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessageKey()
        ], 400);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $expireTime = time() + 3600;
        $tokenPayload = [
            'user_id' => $token->getUser()->getId(),//ID need to be encrypted
            'email'   => $token->getUser()->getEmail(),
            'exp'     => $expireTime
        ];

        $jwt = JWT::encode($tokenPayload, getenv("JWT_SECRET"));

        //we got https
        $useHttps = true;
        setcookie("jwt", $jwt, $expireTime, "/", "", $useHttps, true);

        return new JsonResponse([
            'result' => true
        ]);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'error' => 'Access Denied'
        ]);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
