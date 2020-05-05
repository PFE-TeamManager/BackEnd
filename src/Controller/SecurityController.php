<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $membreRepository;
    private $router;
    private $csrfTokenManager;
    
    public function __construct(MembreRepository $membreRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->membreRepository = $membreRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
    }


    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // return new JsonResponse([
        //     'message' => "Connected",
        //     'username' => 
        // ], 200);

        // dd($error);
        dd($lastUsername);
        // exit();

        // return $this->render('security/login.html.twig', [
        //     'last_username' => $lastUsername,
        //     'error'         => $error,
        // ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }
}
