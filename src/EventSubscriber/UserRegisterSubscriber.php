<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE],
        ];
    }

    public function hashPassword(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        //when we modify password , the membre is obliged to have another Token
        if ( !$user instanceof User || 
             !in_array($method, [Request::METHOD_POST,Request::METHOD_PUT] ) ) {
            return;
        }

        // It is a Membre, we need to hash password here
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );

        // Create confirmation token
        // $user->setConfirmationToken(
        //     $this->tokenGenerator->getRandomSecureToken()
        // );

        // Send e-mail here...
        //$this->mailer->sendConfirmationEmail($user);
    }
}
