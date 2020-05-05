<?php

namespace App\Controller;

use App\Entity\Membre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class MembreController extends AbstractController
{
    public function register(EntityManagerInterface $em,Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $membre = new Membre();

        $email                  = $request->request->get("email");
        $password               = $request->request->get("password");
        $passwordConfirmation   = $request->request->get("password_confirmation");

        $errors = [];
        if($password != $passwordConfirmation)
        {
            $errors[] = "Password does not match the password confirmation.";
        }

        if(strlen($password) < 6)
        {
            $errors[] = "Password should be at least 6 characters.";
        }

        if(!$errors){
                $membre->setPassword($passwordEncoder->encodePassword($membre,$password));
                $membre->setEmail($email);
                $membre->setCreatedBy(4);
            try
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($membre);
                $em->flush();

                return new JsonResponse([
                    "Message" => "Member Successfully Registered!",
                    "membre" => $membre
                ], 200);
            }
            catch(UniqueConstraintViolationException $e)
            {
               $errors[] = "The email provided already has an account!";
            }
            catch(\Exception $e)
            {
               $errors[] = "Unable to save new member at this time.";
            }
        }

        return new JsonResponse([
            'errors' => $errors
        ], 400);
    }

}
