<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    public function __construct(
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $tokenManager
    )
    {
        $this->validator = $validator;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
    }

    public function __invoke(User $data)
    {
        $this->validator->validate($data);

        $encodedOldPassword = $this->userPasswordEncoder->isPasswordValid($data, $data->getOldPassword());

        if( $encodedOldPassword ) {
            //The New Password Checks with regex, entity regex not working
            if( ($data->getNewPassword() == $data->getNewRetypedPassword()) ){
                $data->setPassword(
                    $this->userPasswordEncoder->encodePassword(
                        $data, $data->getNewPassword()
                    )
                );
    
                // After password change, old tokens are still valid
                $data->setPasswordChangeDate(time());
    
                $this->entityManager->flush();
    
                $token = $this->tokenManager->create($data);
    
                return new JsonResponse(['token' => $token]);
    
                // Validator is only called after we return the data from this action!
                // Only hear it checks for user current password, but we've just modified it!
    
                // Entity is persisted automatically, only if validation pass
            } else {
                return new JsonResponse([
                    'errors' => 'Something is wrong! Please Check The passwods'
                ], 400);
            }
        } else {
            return new JsonResponse([
                'errors' => 'Put The right Old Password'
            ], 400);
        }
    }
}
